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
     * @return \Silex\Application
     */
    public function getApp();

    /**
     * @param \Silex\Application $app
     *
     * @return $this
     */
    public function setApp(\Silex\Application $app);
}
