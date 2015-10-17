<?php

namespace BasicBlog\Post;

/**
 * Class PostData
 *
 * Post data
 *
 * @package BasicBlog\Post
 */
class PostData
{
    /**
     * @var string
     */
    const SQL_SELECT_SINGLE_POST_BY_ID = 'SELECT * FROM posts WHERE post_id = ?';

    /**
     * @var string
     */
    const SQL_SELECT_ALL_POSTS_SORTED_CREATED_ASC = 'SELECT * FROM posts ORDER BY created ASC';

    /**
     * @var string
     */
    const SQL_SELECT_POST_CONTENT_BY_ID = 'SELECT * FROM post_content WHERE post_id = ?';

    /**
     * @var string
     */
    const MESSAGE_NOT_INTEGER = 'Provided parameter is not an integer.';

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
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 0);
        }

        $sql = static::SQL_SELECT_SINGLE_POST_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 1);
        }

        return $data;
    }

    /**
     * Fetch a list of post data records
     *
     * @throw \UnexpectedValueException
     *
     * @return array
     */
    public function fetchPostCollectionData()
    {
        $sql = static::SQL_SELECT_ALL_POSTS_SORTED_CREATED_ASC;
        $data = $this->app['db']->fetchAll($sql);

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 2);
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
            throw new \InvalidArgumentException(static::MESSAGE_NOT_INTEGER, 3);
        }

        $sql = static::SQL_SELECT_POST_CONTENT_BY_ID;
        $data = $this->app['db']->fetchAssoc($sql, array((int) $id));

        if ($data === false) {
            throw new \UnexpectedValueException(static::MESSAGE_NO_RESULT_FOUND, 4);
        }

        return $data;
    }

//
//    /**
//     * Update a single post data record by a provided id
//     *
//     * @param $id
//     */
//    public function updatePostDataById($id)
//    {
//
//    }

}
