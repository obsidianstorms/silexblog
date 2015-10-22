<?php

namespace BasicBlog\Common;

/**
 * interface ApplicationAwareTrait
 *
 * @package BasicBlog\Common
 */
trait ApplicationAwareTrait
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * {@inheritDoc}
     */
    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * {@inheritDoc}
     */
    public function setApp(\Silex\Application $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDb()
    {
        return $this->app['db'];
    }

    /**
     * {@inheritDoc}
     */
    public function getSession()
    {
        return $this->app['session'];
    }
}
