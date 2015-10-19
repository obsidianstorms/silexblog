<?php

namespace BasicBlog\Author;

use BasicBlog\Security\Password;

/**
 * Class Author
 *
 * Representation of a complete author object
 *
 * @package BasicBlog\Author
 */
class Author
{
    /**
     * @var string
     */
    const MESSAGE_INVALID_INTEGER = 'Expected integer.';

    /**
     * @var string
     */
    const MESSAGE_INVALID_STRING = 'Expected string.';

    /**
     * @var integer
     */
    protected $authorId;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * @var object Password
     */
    protected $password;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param $id int
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setAuthorId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_INTEGER,
                0
            );
        }
        $this->authorId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setEmail($email)
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                1
            );
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @return Password
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param $hash string
     *
     * @return static
     */
    public function setPasswordHash($hash)
    {
        $this->passwordHash = $hash;
        return $this;
    }

    /**
     * @return Password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $hash string
     *
     * @return static
     */
    public function setPassword(Password $object)
    {
        $this->password = $object;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setFirstName($firstName)
    {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        if ($firstName === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                2
            );
        }
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setLastName($lastName)
    {
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        if ($lastName === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                3
            );
        }
        $this->lastName = $lastName;
        return $this;
    }
}
