<?php

namespace BasicBlog\Author;

/**
 * Class AuthorData
 *
 * Post data
 *
 * @package BasicBlog\Author
 */
class AuthorData
{
    /**
     * @var string
     */
    const SQL_SELECT_AUTHOR_BY_ID = 'SELECT * FROM authors WHERE author_id = ?';

    /**
     * @var string
     */
    const SQL_SELECT_AUTHOR_BY_EMAIL = 'SELECT * FROM authors WHERE email = ?';

    /**
     * @var string
     */
    const MESSAGE_NOT_INTEGER = 'Provided parameter is not an integer.';

    /**
     * @var string
     */
    const MESSAGE_NOT_EMAIL = 'Provided parameter is not an email.';

    /**
     * @var string
     */
    const MESSAGE_NO_RESULT_FOUND = 'Query found no matching results.';

    /**
     * @var \Silex\Application
     */
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    /**
     * Fetch a single author data record by a provided id
     *
     * @param $id
     *
     * @throw \InvalidArgumentException
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchAuthorDataById($id)
    {
        if (!is_integer($id)) {
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 0);
        }

        $sql = static::SQL_SELECT_AUTHOR_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int)$id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 1);
        }

        return $data;
    }

    /**
     * Fetch a single author data record by a provided email
     *
     * @param $email
     *
     * @throw \InvalidArgumentException
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchAuthorDataByEmail($email)
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_NOT_EMAIL,
                2
            );
        }

        $sql = static::SQL_SELECT_AUTHOR_BY_EMAIL;
        $data = $this->app['db']->fetchAssoc($sql, array($email));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 3);
        }

        return $data;
    }

}