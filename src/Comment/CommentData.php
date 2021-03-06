<?php

namespace BasicBlog\Comment;

use BasicBlog\Common\ApplicationAwareInterface;
use BasicBlog\Common\ApplicationAwareTrait;

/**
 * Class CommentData
 *
 * Comment data
 *
 * @package BasicBlog\Comment
 */
class CommentData implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

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
    const SQL_SELECT_COMMENTS_OF_POST_SORTED_CREATED_ASC = '
        SELECT *
        FROM comments
        WHERE post_id = ?
        ORDER BY created ASC
    ';


    /**
     * @param $data
     *
     * @return int
     */
    public function create($data)
    {
        $result = $this->app['db']->insert('comments', $data);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 0);
        }

        $id = $this->app['db']->lastInsertId();

        return $id;
    }

    /**
     * Fetch a list of comment data records for given post id
     *
     * @param $id int
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchCommentsByPostId($id)
    {
        $sql = static::SQL_SELECT_COMMENTS_OF_POST_SORTED_CREATED_ASC;
        $data = $this->app['db']->fetchAll($sql, [(int) $id]);

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 2);
        }

        return $data;
    }

    /**
     * @param $id int
     *
     * @return int
     */
    public function delete($id)
    {
        $result = $this->app['db']->delete('comments', ['comment_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 9);
        }
        return $id;
    }

    /**
     * @param $id int
     *
     * @return int
     */
    public function deleteAllForPost($id)
    {
        $result = $this->app['db']->delete('comments', ['post_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 10);
        }
        return $id;
    }
}
