<?php

namespace BasicBlog\Post;

use BasicBlog\Comment\CommentData;
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

        $post_id = $postDataObject->create($dataToInsert);

        // Save data to database
        $dataToInsertBody = [
            'post_id' => $post_id,
            'body' => $validData['body'],
        ];

        $content_id = $postDataObject->createContent($dataToInsertBody);

        if ($post_id && $content_id) {
            return $post_id;
        }
        return false;
    }

    /**
     * @param $app Application
     * @param $post_id int
     * @param $data array
     *
     * @return bool|mixed
     */
    public function update(Application $app, $post_id, array $data)
    {
        // Empty field check
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('Title is empty.', 1);
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

        $formFieldFilters = [
            'post_id' => FILTER_VALIDATE_INT,
        ];
        $validId = $this->checkDataIntegrity(['post_id' => $post_id], $formFieldFilters);


        $postDataObject = new PostData($app);

        $resultTitle = $postDataObject->update($validId['post_id'], ['title' => $validData['title']]);
        $resultBody = $postDataObject->updateContent($validId['post_id'], ['body' => $validData['body']]);

        if ($resultTitle && $resultBody) {
            return $post_id;
        }
        return false;
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

    /**
     * Fetch a post record
     *
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function delete(Application $app, $id)
    {
        $postDataObject = new PostData($app);

        $formFieldFilters = [
            'post_id' => FILTER_VALIDATE_INT,
        ];
        $validData = $this->checkDataIntegrity(['post_id' => $id], $formFieldFilters);

        $postData = $postDataObject->delete($validData['post_id']);
        $postContentData = $postDataObject->deleteContent($validData['post_id']);

        $commentDataObject = new CommentData($app);
        $commentData = $commentDataObject->deleteAllForPost($validData['post_id']);

        if ($postData && $postContentData && $commentData) {
            return true;
        }
        return false;
    }
}
