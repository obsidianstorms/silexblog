<?php

namespace BasicBlog\Post;

use BasicBlog\Author\Author;

/**
 * Class PostHydrator
 *
 * Hydrator for a Post object
 *
 * @package BasicBlog\Post
 */
class PostHydrator
{
    /**
     * @var string
     */
    const MESSAGE_INVALID_ARRAY = 'Array was empty.';

    /**
     * @var string
     */
    const MESSAGE_EXPECTED_KEY_NOT_FOUND = 'Did not find expected data key [%s].';

    /**
     * @var array
     */
    protected $EXPECTED_CONTENT_RECORD = [
        'body'
    ];

    /**
     * @var array
     */
    protected $EXPECTED_REFERENCE_RECORD = [
        'post_id',
        'author_id',
        'title',
        'created',
        'updated',
    ];

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * Setter for injection of Application for the authorship hydration
     *
     * @param \Silex\Application $app
     *
     * @return static
     */
    public function setApp(\Silex\Application $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Getter for testing purposes only
     *
     * @return \Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param $object Post
     * @param $data array
     *
     * @throw \InvalidArgumentException
     *
     * @returns Post
     */
    public function hydrate(Post $object, array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException(static::MESSAGE_INVALID_ARRAY, 0);
        }
        foreach ($this->EXPECTED_CONTENT_RECORD as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        static::MESSAGE_EXPECTED_KEY_NOT_FOUND,
                        $key
                    ),
                    1
                );
            }
        }
        $object->setBody($data['body']);
        $object = $this->hydrateReference($object, $data);
        return $object;
    }

    /**
     * @param $object Post
     * @param $data array
     *
     * @throw \InvalidArgumentException
     *
     * @return Post
     */
    public function hydrateReference(Post $post, array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException(static::MESSAGE_INVALID_ARRAY, 2);
        }
        foreach ($this->EXPECTED_REFERENCE_RECORD as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        static::MESSAGE_EXPECTED_KEY_NOT_FOUND,
                        $key
                    ),
                    3
                );
            }
        }
        $post->setPostId($data['post_id']);
        $post->setAuthorId($data['author_id']);
        $post->setTitle($data['title']);
        $post->setCreated($data['created']);
        $post->setUpdated($data['updated']);

        $authorFactory = new AuthorFactory();
        $author = $authorFactory->fetch();

        $authorDataObject = new AuthorData($this->app);
        $author = new AuthorHydrator(
            new Author(),
            $authorDataObject->fetchAuthorDataById($post->getAuthorId())
        );
        $post->setAuthor($author);

        return $post;
    }
}
