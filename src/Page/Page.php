<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostFactory;
use BasicBlog\Author\AuthorFactory;
use BasicBlog\Comment\CommentFactory;
use BasicBlog\Commentator\CommentatorFactory;

/**
 * Class Page
 *
 * Controller for route status
 */
class Page
{
    /**
     * @var string Exception catching message
     */
    const MESSAGE_CAUGHT_EXCEPTION = 'Caught exception message [%s] with code [%s].';


    const DEFAULT_SUCCESSFUL_MESSAGE = 'Successful response: ';
    const DEFAULT_SUCCESSFUL_LOGGING = 'Status route example: ';

    /**
     * Build menu
     *
     * @param Application $app
     */
    protected function menu(Application $app)
    {
        $menu = '';
        if (is_null($app['session']->get('author'))
            && is_null($app['session']->get('commentator'))
        ) {
            $menu .= $app['twig']->render('sections/menu.loggedout.twig');
        } else {
            $menu .= $app['twig']->render('sections/menu.loggedin.twig');
        }
        return $menu;
    }

    /**
     * Indicates index application status
     */
    public function index(Application $app)
    {
        // preset
        $requestResponseCode = 200;

        // Fetch Post list
        $factoryObject = new PostFactory();
        try {
            $result = $factoryObject->fetchAll($app);
            if (!$result) {
                $requestResponseCode = 400;
            }
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        }

        // Render page sections
        $content = $this->menu($app);

        if (isset($message)) {
            $content .= $app['twig']->render('sections/error.twig', ['message' => $message]);
        }

        //todo authorship display
        if (!is_null($app['session']->get('author'))) {
            $content .= $app['twig']->render('sections/form.add.post.twig');
        }

        $adminLoggedIn = false;
        if (!is_null($app['session']->get('author'))) {
            $adminLoggedIn = true;
        }
        if (isset($result) && is_array($result)) {
            $content .= $app['twig']->render('sections/list.post.twig', ['posts' => $result, 'admin' => $adminLoggedIn]);
        }
        // Return page
        return new Response($content, $requestResponseCode);

        //todo: collection of objects with individual authorship rather than just array loop
    }

    public function viewLogin(Application $app, $user)
    {
        if (!is_null($app['session']->get('author'))
            || !is_null($app['session']->get('commentator'))
        ) {
            return $app->redirect('/');
        }

        $requestResponseCode = 200;
        $content = $this->menu($app);
        switch ($user) {
            case 'author':
                $content .= $app['twig']->render('sections/form.login.author.twig');
                break;
            case 'commentator': //todo: login from post comment
                $content .= $app['twig']->render('sections/form.login.commentator.twig');
                break;
            default:
                return $app->redirect('/');
        }
        return new Response($content, $requestResponseCode);
    }

    public function viewRegister(Application $app, $user)
    {
        if (!is_null($app['session']->get('author'))
            || !is_null($app['session']->get('commentator'))
        ) {
            return $app->redirect('/');
        }

        $requestResponseCode = 200;
        $content = $this->menu($app);
        switch ($user) {
            case 'author':
                $content .= $app['twig']->render('sections/form.register.author.twig');
                break;
            case 'commentator': //todo: register from post comment
                $content .= $app['twig']->render('sections/form.register.commentator.twig');
                break;
            default:
                return $app->redirect('/');
        }
        return new Response($content, $requestResponseCode);
    }

    /**
     * Logout
     *
     * @param Application $app
     */
    public function logout(Application $app)
    {
        if (!is_null($app['session']->get('author'))) {
            $user = new AuthorFactory();
        } elseif (!is_null($app['session']->get('commentator'))) {
            $user = new CommentatorFactory();
        } else {
            $app['monolog']->addError('Session found but not author nor commentator.');
            return $app->redirect('/');
        }
        $user->logout($app);

        return $app->redirect('/'); //todo: "thank you for logging out" message in session?
    }

    /**
     * Indicates viewPost application status
     *
     * @param $app Application
     * @param $post_id integer
     *
     * @return Response
     */
    public function viewReadPost(Application $app, $post_id)
    {
        $content = $this->menu($app);
        $requestResponseCode = 200;

        // Filter and validation
        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            $requestResponseCode = 400;
        }

        // Fetch post data
        $factoryObject = new PostFactory();
        try {
            $post = $factoryObject->fetch($app, $id);
            if (!$post) {
                $requestResponseCode = 400;
            }
        } catch (\InvalidArgumentException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Invalid query.';
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Failed to retrieve data.';
            $requestResponseCode = 400;
        }

        // Fetch comment data
        $factoryComment = new CommentFactory();
        try {
            $comments = $factoryComment->fetchAll($app, $post['post_id']);
            //todo: need authorship for comments
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        }

        // Content Display
        if (isset($message)) {
            $content .= $app['twig']->render('sections/error.twig', ['message' => $message]);
        }

        $adminLoggedIn = false;
        if (!is_null($app['session']->get('author'))) {
            $adminLoggedIn = true;
        }
        if (isset($post) && is_array($post)) {
            $content .= $app['twig']->render('sections/view.post.twig', ['post' => $post, 'admin' => $adminLoggedIn]);
        }

        if (isset($comments) && is_array($comments)) {
            $content .= $app['twig']->render('sections/list.comment.twig', ['comments' => $comments, 'admin' => $adminLoggedIn]);
        }

        if (!is_null($app['session']->get('commentator'))) {
            $content .= $app['twig']->render('sections/form.add.comment.twig', ['post_id' => $post['post_id']]);
        }

        return new Response($content, $requestResponseCode);
    }

    /**
     * Indicates viewPost application status
     *
     * @param $app Application
     * @param $post_id integer
     *
     * @return Response
     */
    public function viewEditPost(Application $app, $post_id)
    {
        $content = $this->menu($app);
        $requestResponseCode = 200;

        // Filter and validation
        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            $requestResponseCode = 400;
        }

        // Fetch post data
        $factoryObject = new PostFactory();
        try {
            $post = $factoryObject->fetch($app, $id);
            if (!$post) {
                $requestResponseCode = 400;
            }
        } catch (\InvalidArgumentException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Invalid query.';
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Failed to retrieve data.';
            $requestResponseCode = 400;
        }

        if (isset($message)) {
            $content .= $app['twig']->render('sections/error.twig', ['message' => $message]);
        }

        if (isset($post) && is_array($post)) {
            $content .= $app['twig']->render('sections/form.update.post.twig', ['post' => $post]);
        }

        return new Response($content, $requestResponseCode);
    }

    public function newUser(Application $app, $user)
    {
        switch ($user) {
            case 'author':
                $factoryObject = new AuthorFactory();
                $resultFalseMessage = 'Found an existing author, unable to register another.';
                $resultTrueMessage = 'Added author.';
                break;
            case 'commentator':
                $factoryObject = new CommentatorFactory();
                $resultFalseMessage = 'Failed registering commentary user. Reason: unknown.';
                $resultTrueMessage = 'Successfully registered to comment.';
                break;
            default:
                return $app->redirect('/');
        }

        try {
            $result = $factoryObject->create($app, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response($resultFalseMessage, 400);
        }
        return $app->redirect('/');
    }

    /**
     * Indicates login application status
     */
    public function validateLogin(Application $app, $user)
    {
        $resultFalseMessage = 'Failed logging in. Reason: unknown.';
        $resultTrueMessage = 'Successfully logged in.';
        //todo: session messages carry over redirects

        switch ($user) {
            case 'author':
                $factoryObject = new AuthorFactory();
                break;
            case 'commentator':
                $factoryObject = new CommentatorFactory();
                break;
            default:
                return $app->redirect('/');
        }

        try {
            $result = $factoryObject->login($app, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response($resultFalseMessage, 400);
        }
        return $app->redirect('/');
    }

    /**
     * Indicates newPost application status
     */
    public function newPost(Application $app)
    {
        $factoryObject = new PostFactory();

        try {
            $result = $factoryObject->create($app, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Failed to add post.', 400);
        }
        return $app->redirect('/');
    }

    /**
     * Indicates newComment application status
     */
    public function newComment(Application $app, $post_id)
    {
        $factoryObject = new CommentFactory();

        try {
            $result = $factoryObject->create($app, $post_id, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Failed to add comment.', 400);
        }
        return $app->redirect('/post/' . $post_id);
    }

    /**
     * Indicates newPost application status
     */
    public function editPost(Application $app, $post_id)
    {
        $factoryObject = new PostFactory();

        try {
            $result = $factoryObject->update($app, $post_id);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Failed to update post.', 400);
        }
        return new Response("Updated Post.", 200);
    }

    /**
     * Indicates newPost application status
     */
    public function removePost(Application $app, $post_id)
    {
        $factoryObject = new PostFactory();

        try {
            $result = $factoryObject->delete($app, $post_id);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Failed to remove post.', 400);
        }
        return $app->redirect('/');
    }

    /**
     * Indicates newPost application status
     */
    public function removeComment(Application $app, $post_id, $comment_id)
    {
        $factoryObject = new CommentFactory();

        try {
            $result = $factoryObject->delete($app, $comment_id);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Failed to remove comment.', 400);
        }
        return $app->redirect('/post/' . $post_id);
    }
}