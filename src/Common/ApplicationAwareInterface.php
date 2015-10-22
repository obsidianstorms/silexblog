<?php

namespace BasicBlog\Common;

/**
 * interface ApplicationAwareInterface
 *
 * @package BasicBlog\Common
 */
interface ApplicationAwareInterface
{
    /**
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app);

    /**
     * @return \Silex\Application
     */
    public function getApp();

    /**
     * @param \Silex\Application $app
     *
     * @return static
     */
    public function setApp(\Silex\Application $app);

    /**
     * @return \Silex\Provider\DoctrineServiceProvider
     */
    public function getDb();

    /**
     * @return \Silex\Provider\SessionServiceProvider
     */
    public function getSession();
}
