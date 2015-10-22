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
     * @param $app Application
     * @param $data array
     *
     * @return bool|mixed
     */
    public function login(Application $app, array $data);

    /**
     * @param $app Application
     *
     * @return bool|mixed
     */
    public function logout(Application $app);
}
