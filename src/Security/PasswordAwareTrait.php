<?php

namespace BasicBlog\Security;

use BasicBlog\Security\Password;

/**
 * trait PasswordAwareTrait
 *
 * @package BasicBlog\Security
 */
trait PasswordAwareTrait
{
    /**
     * @var object
     */
    protected $password;

    /**
     * @return Password
     */
    public function getPasswordObject()
    {
        return $this->password;
    }

    /**
     * @param Password $object
     *
     * @return static
     */
    public function setPasswordObject(Password $object)
    {
        $this->password = $object;
        return $this;
    }
}
