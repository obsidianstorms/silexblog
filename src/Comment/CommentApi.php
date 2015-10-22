<?php

namespace BasicBlog\Comment;

use BasicBlog\Commentator\CommentatorData;
use BasicBlog\Common\DataAwareInterface;
use BasicBlog\Common\DataAwareTrait;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class CommentApi
 *
 * Handle Comment Objects
 *
 * @package BasicBlog\Comment
 */
class CommentApi implements DataAwareInterface
{
    use ValidationTrait;
    use DataAwareTrait;

    /**
     * @param $post_id int|string
     * @param $data array
     *
     * @return bool|mixed
     */
    public function create($post_id, array $data)
    {
        // Empty field check
        if (empty($data['body'])) {
            throw new \InvalidArgumentException('Body is empty.', 2);
        }

        // Filtering Raw Data
        $formFieldFilters = [
            'body'  => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        $idFieldFilters = [
            'post_id'  => FILTER_VALIDATE_INT,
        ];
        $validPostId = $this->checkDataIntegrity(['post_id' => $post_id], $idFieldFilters);

        // Authorship data
        $formFieldFilters = [
            'commentator_id' => FILTER_VALIDATE_INT,
        ];

        $dataObject = $this->getDataObject();
        $commentator = $dataObject->getSession()->get('commentator');
        $validCommentatorData = $this->checkDataIntegrity($commentator, $formFieldFilters);

        // Save data to database
        $dataToInsert = [
            'commentator_id' => $validCommentatorData['commentator_id'],
            'post_id' => $validPostId['post_id'],
            'body'     => $validData['body'],
        ];

        $comment_id = $dataObject->create($dataToInsert);

        return $comment_id;
    }

    /**
     * Fetch a list of comments records
     *
     * @param $post_id int
     *
     * @return array
     */
    public function fetchAll($post_id)
    {
        // Comment data
        $commentDataObject = $this->getDataObject();
        $commentRecords = $commentDataObject->fetchCommentsByPostId($post_id);

        $records = [];
        $app = $commentDataObject->getApp();
        // Commentator data
        foreach ($commentRecords as $comment) {
            $commentatorDataObject = new CommentatorData($app);
            $commentator = $commentatorDataObject->fetchCommentatorBasicDataById($comment['commentator_id']);
            $records[] = array_merge($comment, $commentator);
        }

        return $records;
    }

    /**
     * Fetch a post record
     *
     * @param $id integer
     *
     * @return array
     */
    public function delete($id)
    {
        $commentDataObject = $this->getDataObject();
        $commentData = $commentDataObject->delete($id);

        if ($commentData) {
            return true;
        }
        return false;
    }

    /**
     * Delete a post record
     *
     * @param $id integer
     *
     * @return array
     */
    public function deleteAllForPost($id)
    {
        $dataObject = $this->getDataObject();

        $formFieldFilters = [
            'post_id' => FILTER_VALIDATE_INT,
        ];
        $validData = $this->checkDataIntegrity(['post_id' => $id], $formFieldFilters);

        $commentData = $dataObject->deleteAllForPost($validData['post_id']);

        if ($commentData) {
            return true;
        }
        return false;
    }
}
