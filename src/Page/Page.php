<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostFactory;

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
        return $app['twig']->render('home.twig', array('name' => ['value1', 'value2']));

        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'index';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'index');
        return new Response($text, 200);

        $data = new PostCollectionFactory();
        $data->fetch($app);

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

        $data = new PostFactory();
        try {
            $data->fetch($app, $id);
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