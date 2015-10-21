<?php

namespace BasicBlog\Commentator;

use BasicBlog\Common\DataInterface;
use BasicBlog\Common\DataTrait;

/**
 * Class CommentatorData
 *
 * Commentator data
 *
 * @package BasicBlog\Commentator
 */
class CommentatorData implements DataInterface
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
    const SQL_SELECT_COMMENTATOR_BY_ID = 'SELECT * FROM commentators WHERE commentator_id = ?';

    /**
     * @var string (not password hash)
     */
    const SQL_SELECT_COMMENTATOR_BASICS_BY_ID = 'SELECT commentator_id, username
FROM commentators WHERE commentator_id = ?';

    /**
     * @var string
     */
    const SQL_SELECT_COMMENTATOR_BY_USERNAME = 'SELECT * FROM commentators WHERE username = ?';

    public function __construct(\Silex\Application $app)
    {
        $this->setApp($app);
    }

    public function doesUsernameExist($username)
    {
        try {
            $data = $this->fetchCommentatorByUsername($username);
        } catch (\UnexpectedValueException $e) {
            $data = false; //none found, can proceed adding
        }

        if ($data === false) {
            $this->app['monolog']->addInfo("Query found no matching commentators.");
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
     * @return int
     */
    public function create($data)
    {
        $result = $this->app['db']->insert('commentators', $data);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 0);
        }

        $id = $this->app['db']->lastInsertId();

        return $id;
    }

    /**
     * Fetch a commentator data record by id, not password
     *
     * @param $id int
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchCommentatorBasicDataById($id)
    {
        $sql = static::SQL_SELECT_COMMENTATOR_BASICS_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 1);
        }

        return $data;
    }

    /**
     * Fetch a commentator data record by id
     *
     * @param $id int
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchCommentatorFullDataById($id)
    {
        $sql = static::SQL_SELECT_COMMENTATOR_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 2);
        }

        return $data;
    }

    /**
     * Fetch a commentator data record by username
     *
     * @param $username string
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchCommentatorByUsername($username)
    {
        $sql = static::SQL_SELECT_COMMENTATOR_BY_USERNAME;
        $data = $this->app['db']->fetchAssoc($sql, array( $username));

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
    public function updatePassword($id, $hash)
    {
        $result = $this->app['db']->update('commentators', ['password_hash' => $hash], ['commentator_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 4);
        }

        return $id;
    }

}
