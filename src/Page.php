<?php

namespace BasicBlog;

/**
 * Class Page
 *
 * Controller for route status
 */
class Page
{
    /**
     * Indicates application status
     */
    public function index(\Silex\Application $app)
    {
        $app['monolog']->addInfo('Status route example');
        return 'Successful response';
    }
}