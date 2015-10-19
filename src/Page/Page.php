<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostFactory;
use BasicBlog\Author\AuthorFactory;

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
     * Indicates index application status
     */
    public function index(Application $app)
    {
        if (null === $author = $app['session']->get('author')) {
            $content = $app['twig']->render('sections/loginform.twig');
        } else {
            $content = $app['twig']->render('sections/postlist.twig');
        }
        return new Response($content, 200);

//        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'index';
//        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'index');
//        return new Response($text, 200);
//
//        $data = new PostCollectionFactory();
//        $author_id = 1; //todo: session id grab
//        $data->fetchByAuthor($app, $author_id);

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
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            return new Response("Invalid submission.", 400);
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            return new Response("Failed to save author.", 400);
        } catch (\RuntimeException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            return new Response("Failed Processing.", 400);
        }

        if (!$result) {
            $content = "An author already exists.";
            return new Response($content, 400);
        } else {
            $content = "Successful creation.";
            // If successfully added author, retrieve full record by the returned id
            $author = $factoryObject->fetchBasics($app, $result);
            $app['session']->set('author', array(
                'id' => $author->getAuthorId(),
                'email' => $author->getEmail(),
            ));
            $app->redirect('/');
        }
//        return new Response($content, 200);
    }

    /**
     * Indicates login application status
     */
    public function login(Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'login';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'login');
        return new Response($text, 200);
    }

    /**
     * Indicates newPost application status
     */
    public function newPost(Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'newPost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'newPost');
        return new Response($text, 200);
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
        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            return new Response($message, 400);
        }

        $factoryObject = new PostFactory();
        try {
            $dataObject = $factoryObject->fetch($app, $id);
            $message = "Found data";
            $app['monolog']->addInfo($message);
            return new Response($message, 200);
        } catch (\InvalidArgumentException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            return new Response("Invalid query.", 400);
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            return new Response("Failed to retrieve data.", 400);
        }
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
     * Indicates newComment application status
     */
    public function newComment(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'newComment';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'newComment');
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