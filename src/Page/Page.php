<?php

namespace BasicBlog\Page;

use BasicBlog\Commentator\CommentatorFactory;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostFactory;
use BasicBlog\Author\AuthorFactory;
use BasicBlog\Comment\CommentFactory;
use Zend\Feed\Reader\Collection\Author;

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
        if (null === $author = $app['session']->get('author')
            || null === $commentator = $app['session']->get('commentator')
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
        if (is_null($app['session']->get('author'))
            || is_null($app['session']->get('commentator'))
        ) {
            return $app->redirect('/login');
        }

        $requestResponseCode = 200;
        $content = $this->menu($app);

        //todo authorship display

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

        if (isset($message)) {
            $content .= $app['twig']->render('sections/error.twig', ['message' => $message]);
        }

        $content .= $app['twig']->render('sections/form.add.post.twig');

        if (is_array($result)) {
            $content .= $app['twig']->render('sections/list.post.twig', ['posts' => $result]);
        }
        return new Response($content, $requestResponseCode);

        //todo: collection of objects with individual authorship rather than just array loop
    }

    /**
     * Choose login type
     *
     * @param Application $app
     */
    public function pickLogin(Application $app)
    {
        $content = $app['twig']->render('sections/view.login.pick.twig');
        return new Response($content, 200);
    }

    /**
     * Logout
     *
     * @param Application $app
     */
    public function logout(Application $app)
    {
        if (is_null($app['session']->get('author'))) {
            $user = new AuthorFactory();
        } elseif (is_null($app['session']->get('commentator'))) {
            $user = new CommentatorFactory();
        } else {
            $app['monolog']->addError('Session found but not author nor commentator.');
            return $app->redirect('/');
        }
        $user->logout($app);

        return $app->redirect('/'); //todo: "thank you for logging out" message in session?
    }

    /**
     * Indicates registration page status
     */
    public function register(Application $app)
    {
        $content = $app['twig']->render('sections/registerauthor.twig');
        return new Response($content, 200);
    }

    /**
     * Indicates new author creation status
     */
    public function newAuthor(Application $app)
    {
        $factoryObject = new AuthorFactory();

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
            return new Response('Found Authors.', 400);
        }
        return new Response("Added Author {$result}.", 200);
    }

    /**
     * Indicates login application status
     */
    public function login(Application $app)
    {
        $content = $app['twig']->render('sections/loginform.twig');
        return new Response($content, 200);
    }

    /**
     * Indicates login application status
     */
    public function validateLogin(Application $app)
    {
        $factoryObject = new AuthorFactory();

        try {
            $result = $factoryObject->login($app, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        } catch (\RuntimeException $e) {
            $message = $e->getMessage();
            return new Response($message, 400);
        }

        if (!$result) {
            return new Response('Invalid Login.', 400);
        }
        return new Response("Login successful.", 200);
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
        return new Response("Added Post.", 200);

    }

    /**
     * Indicates viewPost application status
     *
     * @param $app Application
     * @param $post_id integer
     *
     * @return Response
     */
    public function viewPost(Application $app, $post_id)
    {
        $content = '';
        $requestResponseCode = 200;

        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            $requestResponseCode = 400;
        }

        $factoryObject = new PostFactory();
        try {
            $post = $factoryObject->fetch($app, $id);
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

        if (!$post) {
            $requestResponseCode = 400;
        }

        if (isset($message)) {
            $content .= $app['twig']->render('sections/error.twig', ['message' => $message]);
        }

        if (is_array($post)) {
            $content .= $app['twig']->render('sections/postview.twig', ['post' => $post]);
        }

        // Comments
        $factoryComment = new CommentFactory();
        try {
            $comments = $factoryComment->fetchAll($app, $post['post_id']);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = 400;
        }

        $content .= $app['twig']->render('sections/layout.commentlist.twig');
        if (is_array($comments)) {
            //todo: figure out how to nest render an admin-only delete link in the commentlist.twig loop
            if (null === $author = $app['session']->get('author')) {
                $content .= $app['twig']->render('sections/commentlistadmin.twig', ['comments' => $comments]);
            } else {
                $content .= $app['twig']->render('sections/commentlist.twig', ['comments' => $comments]);
            }
        }

        if (null === $commentator = $app['session']->get('commentator')) {
            $content .= $app['twig']->render('sections/commentform.twig', ['post_id' => $post['post_id']]);
        } else {
            $content .= $app['twig']->render('sections/commentlogin.twig', ['post_id' => $post['post_id']]);
            $content .= $app['twig']->render('sections/commentregister.twig', ['post_id' => $post['post_id']]);
        }

        return new Response($content, $requestResponseCode);
    }

    /**
     * Indicates newComment application status
     */
    public function newComment(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'newComment';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'newComment');
        return new Response($text, 200);
    }

    /**
     * Indicates editPost application status
     */
    public function editPost(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'editPost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'editPost');
        return new Response($text, 200);
    }

    /**
     * Indicates changePost application status
     */
    public function changePost(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'changePost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'changePost');
        return new Response($text, 200);
    }

    /**
     * Indicates removePost application status
     */
    public function removePost(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'removePost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'removePost');
        return new Response($text, 200);
    }

    /**
     * Indicates viewComment application status
     */
    public function viewComment(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'viewComment';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'viewComment');
        return new Response($text, 200);
    }

    /**
     * Indicates removeComment application status
     */
    public function removeComment(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'removeComment';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'removeComment');
        return new Response($text, 200);
    }
}