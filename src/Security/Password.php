<?php

namespace BasicBlog\Security;

/**
 * Class Password
 *
 * @package BasicBlog\Security
 */
class Password
{
    /**
     * @var string
     */
    const MESSAGE_FAILED_CREATING_PASSWORD = 'Password creation failed.';

    /**
     * @var string
     */
    const MESSAGE_FAILED_MATCHING_PASSWORD = 'Password does not match.';

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var bool
     */
    protected $isSecurePassword = true;

    /**
     * Create hash from plain text
     *
     * @param $plainText string
     *
     * @throw \RuntimeException
     *
     * @return static
     */
    public function createHashedPassword($plainText)
    {
        $hash = password_hash($plainText, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new \RuntimeException(static::MESSAGE_FAILED_CREATING_PASSWORD);
        }
        $this->hash = $hash;
        return $this;
    }

    /**
     * Retrieve hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Check if submitted password matches existing password
     *
     * @param $plainText
     * @param $hash
     *
     * @return bool
     */
    public function verifyPassword($plainText, $hash)
    {
        $match = password_verify($plainText, $hash);
        if ($match === true) {
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $this->isSecurePassword = false;
                $this->createHashedPassword($plainText);
            }
        }
        return $match;
    }

    /**
     * @return bool
     */
    public function isSecurePassword()
    {
        return $this->isSecurePassword;
    }
}
