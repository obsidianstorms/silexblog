<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostApi;
use BasicBlog\Author\AuthorApi;
use BasicBlog\Comment\CommentApi;
use BasicBlog\Commentator\CommentatorApi;

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
     * @param Application $app
     *
     * @return bool
     */
    protected function isUser(Application $app)
    {
        if (is_null($app['session']->get('commentator'))) {
            return false;
        }
        return true;
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function index(Application $app)
    {
        // preset
        $requestResponseCode = '200_OK';

        // Fetch Post list
        $apiObject = new PostApi();
        try {
            $result = $apiObject->fetchAll($app);
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
        if ($this->isAdmin($app)) {
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

    /**
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function viewLogin(Application $app, $user)
    {
        $isLoggedIn = $this->isLoggedIn($app);

        if ($isLoggedIn) {
            return $app->redirect('/');
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $isLoggedIn,
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

    /**
     * @param Application $app
     * @param $user string
     *
     * @return Response
     */
    public function viewRegister(Application $app, $user)
    {
        $isLoggedIn = $this->isLoggedIn($app);

        if ($isLoggedIn) {
            return $app->redirect('/');
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $isLoggedIn,
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
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function logout(Application $app)
    {
        if ($this->isAdmin($app)) {
            $user = new AuthorApi();
        } elseif ($this->isUser($app)) {
            $user = new CommentatorApi();
        } else {
            $app['monolog']->addError('Session found but not author nor commentator.');
            return $app->redirect('/');
        }
        $user->logout($app);

        return $app->redirect('/'); //todo: "thank you for logging out" message in session?
    }

    /**
     * @param Application $app
     * @param $post_id
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
        $apiObject = new PostApi();
        try {
            $post = $apiObject->fetch($app, $id);
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
        $factoryComment = new CommentApi();
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
        $apiObject = new PostApi();
        try {
            $post = $apiObject->fetch($app, $id);
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

    /**
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newUser(Application $app, $user)
    {
        switch ($user) {
            case 'author':
                $apiObject = new AuthorApi();
                $resultFalseMessage = 'Found an existing author, unable to register another.';
                $resultTrueMessage = 'Added author.';
                break;
            case 'commentator':
                $apiObject = new CommentatorApi();
                $resultFalseMessage = 'Failed registering commentary user. Reason: unknown.';
                $resultTrueMessage = 'Successfully registered to comment.';
                break;
            default:
                return $app->redirect('/');
        }

        try {
            $result = $apiObject->create($app, $_POST);
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
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function validateLogin(Application $app, $user)
    {
        $resultFalseMessage = 'Failed logging in. Reason: unknown.';
        $resultTrueMessage = 'Successfully logged in.';
        //todo: session messages carry over redirects

        switch ($user) {
            case 'author':
                $apiObject = new AuthorApi();
                break;
            case 'commentator':
                $apiObject = new CommentatorApi();
                break;
            default:
                return $app->redirect('/');
        }

        try {
            $result = $apiObject->login($app, $_POST);
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
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newPost(Application $app)
    {
        $apiObject = new PostApi();

        try {
            $result = $apiObject->create($app, $_POST);
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
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newComment(Application $app, $post_id)
    {
        $apiObject = new CommentApi();

        try {
            $result = $apiObject->create($app, $post_id, $_POST);
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
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPost(Application $app, $post_id)
    {
        $apiObject = new PostApi();

        try {
            $result = $apiObject->update($app, $post_id, $_POST);
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
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removePost(Application $app, $post_id)
    {
        $apiObject = new PostApi();

        try {
            $result = $apiObject->delete($app, $post_id);
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
     * @param Application $app
     * @param $post_id int
     * @param $comment_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeComment(Application $app, $post_id, $comment_id)
    {
        $apiObject = new CommentApi();

        try {
            $result = $apiObject->delete($app, $comment_id);
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