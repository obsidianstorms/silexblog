<?php

namespace BasicBlog\Post;

use BasicBlog\Comment\CommentData;
use BasicBlog\Common\DataAwareInterface;
use BasicBlog\Common\DataAwareTrait;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class PostApi
 *
 * Handle Post Objects
 *
 * @package BasicBlog\Post
 */
class PostApi implements DataAwareInterface
{
    use ValidationTrait;
    use DataAwareTrait;

    /**
     * @param $data array
     *
     * @return bool|mixed
     */
    public function create(array $data)
    {
        // Empty field check
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('Title is empty.', 1);
        }

        $postDataObject = $this->getDataObject();
        $author = $postDataObject->getSession()->get('author');
        //todo: convert to session object?
        if (is_null($author)) {
            throw new \InvalidArgumentException('Author is not logged in.', 2);
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
        $validAuthorData = $this->checkDataIntegrity($author, $formFieldFilters);

        // Save data to database
        $dataToInsert = [
            'author_id' => $validAuthorData['author_id'],
            'title' => $validData['title'],
        ];

        $post_id = $postDataObject->create($dataToInsert);

        if (!$post_id) {
            return false;
        }

        // Save data to database
        $dataToInsertBody = [
            'post_id' => $post_id,
            'body' => $validData['body'],
        ];

        $content_id = $postDataObject->createContent($dataToInsertBody);

        if (!$content_id) {
            return false;
        }
        return $post_id;
    }

    /**
     * @param $post_id int
     * @param $data array
     *
     * @return bool|mixed
     */
    public function update($post_id, array $data)
    {
        // Empty field check
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('Title is empty.', 3);
        }

        $postDataObject = $this->getDataObject();
        $author = $postDataObject->getSession()->get('author');
        if (is_null($author)) {
            throw new \InvalidArgumentException('Author is not logged in.', 4);
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

        $resultTitle = $postDataObject->update($validId['post_id'], ['title' => $validData['title']]);
        $resultBody = $postDataObject->updateContent($validId['post_id'], ['body' => $validData['body']]);

        if (!$resultTitle || !$resultBody) {
            return false;
        }
        return $post_id;
    }

    /**
     * Fetch a list of post records
     *
     * @return array
     */
    public function fetchAll()
    {
        $postDataObject = $this->getDataObject();

        // Fetch data from database
        $records = $postDataObject->fetchPosts();

        return $records;
    }

    /**
     * Fetch a post record
     *
     * @param $id integer
     *
     * @return array
     */
    public function fetch($id)
    {
        $postDataObject = $this->getDataObject();
        $postData = $postDataObject->fetchPostDataById($id);
        $postContentData = $postDataObject->fetchPostContentDataById($id);

        $data = array_merge($postData, $postContentData);

        return $data;
    }

    /**
     * Delete a post record
     *
     * @param $id integer
     *
     * @return array
     */
    public function delete($id)
    {
        $postDataObject = $this->getDataObject();

        $formFieldFilters = [
            'post_id' => FILTER_VALIDATE_INT,
        ];
        $validData = $this->checkDataIntegrity(['post_id' => $id], $formFieldFilters);

        $postData = $postDataObject->delete($validData['post_id']);
        $postContentData = $postDataObject->deleteContent($validData['post_id']);

        if ($postData && $postContentData) {
            return true;
        }
        return false;
    }
}
