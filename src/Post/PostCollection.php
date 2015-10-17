<?php

namespace BasicBlog\Post;

/**
 * Class PostCollection
 *
 * Handle a Colleciton of Post Objects
 *
 * @package BasicBlog\Post
 */
class PostCollection
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @param Post $post
     *
     * @return static
     */
    public function addToCollection(Post $post)
    {
        $this->collection[] = $post;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }



}
