<?php

namespace BasicBlog\Author;

use BasicBlog\Common\DataInterface;
use BasicBlog\Common\DataTrait;

/**
 * Class AuthorData
 *
 * Post data
 *
 * @package BasicBlog\Author
 */
class AuthorData implements DataInterface
{
    use DataTrait;

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
     * @var string
     */
    const SQL_SELECT_AUTHORS = '
        SELECT *
        FROM authors
    ';

    /**
     * @var string
     */
    const SQL_SELECT_AUTHOR_BY_ID = '
        SELECT *
        FROM authors
        WHERE author_id = ?
    ';

    /**
     * @var string
     */
    const SQL_SELECT_AUTHOR_BY_EMAIL = '
        SELECT *
        FROM authors
        WHERE email = ?
    ';

    /**
     * @var string (not password hash)
     */
    const SQL_SELECT_AUTHOR_BASICS_BY_ID = '
        SELECT
            author_id,
            email,
            first_name,
            last_name
        FROM authors
        WHERE author_id = ?
    ';


    /**
     * @return bool
     */
    public function doAuthorsExist()
    {
        $sql = static::SQL_SELECT_AUTHORS;
        $data = $this->app['db']->fetchAssoc($sql);

        if ($data === false) {
            $this->app['monolog']->addInfo("Query found no authors.");
            return false;
        }

        if (count($data) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $result = $this->app['db']->insert('authors', $data);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 0);
        }

        $id = $this->app['db']->lastInsertId();

        return $id;
    }

    /**
     * Fetch a single author data record by a provided id, not fetching password
     *
     * @param $id
     *
     * @throw \InvalidArgumentException
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchAuthorBasicDataById($id)
    {
        if (!is_integer($id)) {
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 0);
        }

        $sql = static::SQL_SELECT_AUTHOR_BASICS_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int)$id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 1);
        }

        return $data;
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

    /**
     * Update password with new hash
     *
     * @param $author_id
     * @param $hash
     *
     * @return mixed
     */
    public function updatePassword($author_id, $hash)
    {
        $result = $this->app['db']->update('authors', ['password_hash' => $hash], ['author_id' => $author_id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 0);
        }

        return $author_id;
    }
}
