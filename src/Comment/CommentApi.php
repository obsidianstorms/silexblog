<?php

namespace BasicBlog\Comment;

use BasicBlog\Commentator\CommentatorData;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class CommentApi
 *
 * Handle Comment Objects
 *
 * @package BasicBlog\Comment
 */
class CommentApi
{
    use ValidationTrait;

    /**
     * @param $app Application
     * @param $post_id int|string
     * @param $data array
     *
     * @return bool|mixed
     */
    public function create(Application $app, $post_id, array $data)
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
        $commentator = $app['session']->get('commentator');
        $validCommentatorData = $this->checkDataIntegrity($commentator, $formFieldFilters);

        // Save data to database
        $dataToInsert = [
            'commentator_id' => $validCommentatorData['commentator_id'],
            'post_id' => $validPostId['post_id'],
            'body'     => $validData['body'],
        ];

        $dataObject = new CommentData($app);

        $comment_id = $dataObject->create($dataToInsert);

        return $comment_id;
    }

    /**
     * Fetch a list of comments records
     *
     * @param $app Application
     * @param $post_id int
     *
     * @return array
     */
    public function fetchAll(Application $app, $post_id)
    {
        // Comment data
        $commentDataObject = new CommentData($app);
        $commentRecords = $commentDataObject->fetchCommentsByPostId($post_id);

        $records = [];
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
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function delete(Application $app, $id)
    {
        $commentDataObject = new CommentData($app);
        $commentData = $commentDataObject->delete($id);

        if ($commentData) {
            return true;
        }
        return false;
    }
}
