<?php

namespace BasicBlog\Post;

use BasicBlog\Common\DataInterface;
use BasicBlog\Common\DataTrait;

/**
 * Class PostData
 *
 * Post data
 *
 * @package BasicBlog\Post
 */
class PostData implements DataInterface
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
    const SQL_SELECT_SINGLE_POST_BY_ID = 'SELECT * FROM posts WHERE post_id = ?';

    /**
     * @var string
     */
    const SQL_SELECT_POSTS_SORTED_CREATED_ASC = 'SELECT * FROM posts ORDER BY created ASC';

    /**
     * @var string
     */
    const SQL_SELECT_POST_CONTENT_BY_ID = 'SELECT * FROM post_content WHERE post_id = ?';


    public function __construct(\Silex\Application $app)
    {
        $this->setApp($app);
    }

    /**
     * @param $data array
     *
     * @return int
     */
    public function create($data)
    {
        $result = $this->app['db']->insert('posts', $data);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 0);
        }

        $id = $this->app['db']->lastInsertId();

        return $id;
    }

    /**
     * @param $data array
     *
     * @return int
     */
    public function createContent($data)
    {
        $result = $this->app['db']->insert('post_content', $data);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 1);
        }

        $id = $this->app['db']->lastInsertId();

        return $id;
    }

    /**
     * @param $id int
     * @param $data array
     *
     * @return int
     */
    public function update($id, $data)
    {
        $timestamp = new \DateTime('now');
        $data['updated'] = $timestamp->format('Y-m-d H:i:s');
        $result = $this->app['db']->update('posts', $data, ['post_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 2);
        }

        return $id;
    }

    /**
     * @param $id int
     * @param $data array
     *
     * @return int
     */
    public function updateContent($id, $data)
    {
        $result = $this->app['db']->update('post_content', $data, ['post_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 3);
        }

        return $id;
    }

    /**
     * @param $id int
     *
     * @return int
     */
    public function delete($id)
    {
        $result = $this->app['db']->delete('posts', ['post_id' => $id]);

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
    public function deleteContent($id)
    {
        $result = $this->app['db']->delete('post_content', ['post_id' => $id]);

        if ($result === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 10);
        }
        return $id;
    }

    /**
     * Fetch a list of post data records
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchPosts()
    {
        $sql = static::SQL_SELECT_POSTS_SORTED_CREATED_ASC;
        $data = $this->app['db']->fetchAll($sql);

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 4);
        }

        return $data;
    }

    /**
     * Fetch a single post data record by a provided id
     *
     * @param $id
     *
     * @throw \InvalidArgumentException
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchPostDataById($id)
    {
        if (!is_integer($id)) {
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 5);
        }

        $sql = static::SQL_SELECT_SINGLE_POST_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 6);
        }

        return $data;
    }

    /**
     * Fetch content for a post id
     *
     * @param $id
     *
     * @throw \InvalidArgumentException
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchPostContentDataById($id)
    {
        if (!is_integer($id)) {
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 7);
        }

        $sql = static::SQL_SELECT_POST_CONTENT_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 8);
        }

        return $data;
    }
}
