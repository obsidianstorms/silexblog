<?php

namespace BasicBlog\Comment;

use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class CommentFactory
 *
 * Handle Comment Objects
 *
 * @package BasicBlog\Comment
 */
class CommentFactory
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
        if (empty($data['body'])) {
            throw new \InvalidArgumentException('Body is empty.', 2);
        }
        if (null === $commentator = $app['session']->get('commentator')) {
            throw new \InvalidArgumentException('Commentator is not logged in.', 3);
        }

        // Filtering Raw Data
        $formFieldFilters = [
            'body'  => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Authorship data
        $formFieldFilters = [
            'commentator_id' => FILTER_VALIDATE_INT,
        ];
        $author = $app['session']->get('commentator');
        $validCommentatorData = $this->checkDataIntegrity($commentator, $formFieldFilters);

        $postDataObject = new PostData($app);

        // Save data to database
        $dataToInsert = [
            'author_id' => $validCommentatorData['author_id'],
            'title'     => $validData['title'],
        ];

        $post_id = $postDataObject->createNewPost($dataToInsert);

        // Save data to database
        $dataToInsertBody = [
            'post_id' => $post_id,
            'body'    => $validData['body'],
        ];

        $content_id = $postDataObject->createNewPostContent($dataToInsertBody);

        return true;
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
        $commentDataObject = new CommentData($app);

        // Fetch data from database
        $records = $commentDataObject->fetchCommentatorFullDataById($post_id);

        return $records;
    }

}
