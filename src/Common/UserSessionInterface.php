<?php

namespace BasicBlog\Common;

use \Silex\Application;
/**
 * interface UserSessionInterface
 *
 * @package BasicBlog\Common
 */
interface UserSessionInterface
{
    /**
     * @param $data array
     *
     * @return bool|mixed
     */
    public function login(array $data);

    /**
     * @return bool
     */
    public function logout();
}
