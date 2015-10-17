<?php

namespace BasicBlog\Post;

use BasicBlog\Author\Author;
use BasicBlog\Author\AuthorData;

/**
 * Class PostFactory
 *
 * Handle Post Objects
 *
 * @package BasicBlog\Post
 */
class PostFactory
{

    /**
     * Fetch a hydrated post object
     *
     * @param $app \Silex\Application
     * @param $id integer
     *
     * @return Post
     */
    public function fetch($app, $id)
    {
        $postDataObject = new PostData($app);
        $postData = $postDataObject->fetchPostDataById($id);
        $postContentData = $postDataObject->fetchPostContentDataById($id);

        $data = array_merge($postData, $postContentData);

        $postHydrator = new PostHydrator();
        $postHydrator->setApp($app);
        try {
            $post = $postHydrator->hydrate(new Post(), $data);
        } catch (\InvalidArgumentException $e) {
            throw new \UnexpectedValueException('Attempted to process bad data.', 0);
        }

        return $post;
    }

}
