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
     * @param Application $app
     *
     * @return bool
     */
    protected function isLoggedIn(Application $app)
    {
        if (is_null($app['session']->get('author'))
            && is_null($app['session']->get('commentator'))
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    protected function isAdmin(Application $app)
    {
        if (is_null($app['session']->get('author'))) {
            return false;
        }
        return true;
    }

    /**
     * Indicates index application status
     */
    public function index(Application $app)
    {
        // preset
        $requestResponseCode = '200_OK';

        // Fetch Post list
        $factoryObject = new PostFactory();
        try {
            $result = $factoryObject->fetchAll($app);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'addPost' => false,
            'posts' => false,
        ];

        if (isset($message)) {
            $pageArgs['message'] = $message;
        }

        //todo authorship display
        if (!is_null($app['session']->get('author'))) {
            $pageArgs['addPost'] = true;
        }

        if (isset($result) && is_array($result)) {
            $pageArgs['posts'] = $result;
        }

        // Return page
        $content = $app['twig']->render('index.twig', $pageArgs);
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

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'form' => false,
        ];

        switch ($user) {
            case 'author':
                $pageArgs['form'] = 'sections/form.login.author.twig';
                break;
            case 'commentator':
                //todo: login from post comment
                $pageArgs['form'] = 'sections/form.login.commentator.twig';
                break;
            default:
                return $app->redirect('/');
        }

        // Return page
        $content = $app['twig']->render('login.twig', $pageArgs);
        return new Response($content);
    }

    public function viewRegister(Application $app, $user)
    {
        if (!is_null($app['session']->get('author'))
            || !is_null($app['session']->get('commentator'))
        ) {
            return $app->redirect('/');
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'form' => false,
        ];

        switch ($user) {
            case 'author':
                $pageArgs['form'] = 'sections/form.register.author.twig';
                break;
            case 'commentator':
                //todo: register from post comment
                $pageArgs['form'] = 'sections/form.register.commentator.twig';
                break;
            default:
                return $app->redirect('/');
        }

        // Return page
        $content = $app['twig']->render('register.twig', $pageArgs);
        return new Response($content);
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
        $requestResponseCode = '200_OK';

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

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'post' => false,
            'comments' => false,
            'addComment' => false,
        ];

        if (isset($message)) {
            $pageArgs['message'] = $message;
        }

        //todo authorship display
        if (!is_null($app['session']->get('commentator'))) {
            $pageArgs['addComment'] = true;
        }

        if (isset($post) && is_array($post)) {
            $pageArgs['post'] = $post;
            $pageArgs['post_id'] = $post['post_id'];
        }

        if (isset($comments) && is_array($comments)) {
            $pageArgs['comments'] = $comments;
        }

        // Return page
        $content = $app['twig']->render('post.twig', $pageArgs);
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
        $requestResponseCode = '200_OK';

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

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'post' => false,
        ];

        if (isset($message)) {
            $pageArgs['message'] = $message;
        }

        if (isset($post) && is_array($post)) {
            $pageArgs['post'] = $post;
        }

        $content = $app['twig']->render('post.edit.twig', $pageArgs);
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
            $result = $factoryObject->update($app, $post_id, $_POST);
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
        return $app->redirect('/post/' . $post_id);
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