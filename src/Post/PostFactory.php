<?php

namespace BasicBlog\Post;

use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class PostFactory
 *
 * Handle Post Objects
 *
 * @package BasicBlog\Post
 */
class PostFactory
{
    use ValidationTrait;

    /**
     * @param $app Application
     * @param $data array
     *
     * @return bool|mixed
     */
    public function create(Application $app, array $data)
    {
        // Empty field check
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('Title is empty.', 1);
        }
        if (empty($data['body'])) {
            throw new \InvalidArgumentException('Body is empty.', 2);
        }
        if (null === $author = $app['session']->get('author')) {
            throw new \InvalidArgumentException('Author is not logged in.', 3);
        }

        // Filtering Raw Data
        $formFieldFilters = [
            'title' => FILTER_SANITIZE_STRING,
            'body' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Authorship data
        $formFieldFilters = [
            'author_id' => FILTER_VALIDATE_INT,
        ];
        $author = $app['session']->get('author');
        $validAuthorData = $this->checkDataIntegrity($author, $formFieldFilters);

        $postDataObject = new PostData($app);

        // Save data to database
        $dataToInsert = [
            'author_id' => $validAuthorData['author_id'],
            'title' => $validData['title'],
        ];

        $post_id = $postDataObject->createNewPost($dataToInsert);

        // Save data to database
        $dataToInsertBody = [
            'post_id' => $post_id,
            'body' => $validData['body'],
        ];

        $content_id = $postDataObject->createNewPostContent($dataToInsertBody);

        return true;
    }

    /**
     * Fetch a list of post records
     *
     * @param $app Application
     *
     * @return array
     */
    public function fetchAll(Application $app)
    {
        $postDataObject = new PostData($app);

        // Fetch data from database
        $records = $postDataObject->fetchPosts();

        return $records;
    }

    /**
     * Fetch a post record
     *
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function fetch(Application $app, $id)
    {
        $postDataObject = new PostData($app);
        $postData = $postDataObject->fetchPostDataById($id);
        $postContentData = $postDataObject->fetchPostContentDataById($id);

        $data = array_merge($postData, $postContentData);

        return $data;
    }
}
