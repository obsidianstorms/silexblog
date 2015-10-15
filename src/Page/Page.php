<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Page
 *
 * Controller for route status
 */
class Page
{
    const DEFAULT_SUCCESSFUL_MESSAGE = 'Successful response: ';
    const DEFAULT_SUCCESSFUL_LOGGING = 'Status route example: ';

    /**
     * Indicates index application status
     */
    public function index(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'index';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'index');
        return new Response($text, 200);
    }

    /**
     * Indicates login application status
     */
    public function login(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'login';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'login');
        return new Response($text, 200);
    }

    /**
     * Indicates newPost application status
     */
    public function newPost(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'newPost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'newPost');
        return new Response($text, 200);
    }

    /**
     * Indicates viewPost application status
     */
    public function viewPost(\Silex\Application $app)
    {
        $text = static::DEFAULT_SUCCESSFUL_MESSAGE . 'viewPost';
        $app['monolog']->addInfo(static::DEFAULT_SUCCESSFUL_LOGGING . 'viewPost');
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