<?php

namespace BasicBlog\Post;

use BasicBlog\Author\Author;

/**
 * Class Post
 *
 * Representation of a complete post object
 *
 * @package BasicBlog\Post
 */
class Post
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
    protected $postId;

    /**
     * @var integer
     */
    protected $authorId;

    /**
     * @var Author
     */
    protected $author;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string timestamp
     */
    protected $created;

    /**
     * @var string timestamp
     */
    protected $updated;

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param $id int
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setPostId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_INTEGER,
                0
            );
        }
        $this->postId = $id;
        return $this;
    }

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
                1
            );
        }
        $this->authorId = $id;
        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param $author Author
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setTitle($title)
    {
        $title = filter_var($title, FILTER_SANITIZE_STRING);
        if ($title === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                2
            );
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setBody($body)
    {
        $body = filter_var($body, FILTER_SANITIZE_STRING);
        if ($body === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                3
            );
        }
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setCreated($created)
    {
        $created = filter_var($created, FILTER_SANITIZE_STRING);
        if ($created === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                4
            );
        }
        $this->created = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param $updated string
     *
     * @throw \InvalidArgumentException
     *
     * @return static
     */
    public function setUpdated($updated)
    {
        $updated = filter_var($updated, FILTER_SANITIZE_STRING);
        if ($updated === false) {
            throw new \InvalidArgumentException(
                static::MESSAGE_INVALID_STRING,
                5
            );
        }
        $this->updated = $updated;
        return $this;
    }

}
