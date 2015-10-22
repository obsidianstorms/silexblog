<?php

namespace BasicBlog\Common;

/**
 * interface DataInterface
 *
 * @package BasicBlog\Common
 */
interface DataInterface
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
}
