<?php

namespace BasicBlog\Common;

/**
 * interface DataTrait
 *
 * @package BasicBlog\Common
 */
trait DataTrait
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
        $this->setApp($app);
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
}
