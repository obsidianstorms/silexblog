<?php

namespace BasicBlog\Post;

/**
 * Class PostCollectionFactory
 *
 * Handle a Colleciton of Post Objects
 *
 * @package BasicBlog\Post
 */
class PostCollectionFactory
{
    /**
     * @var string Exception catching message
     */
    const MESSAGE_CAUGHT_EXCEPTION = 'Caught exception message [%s] with code [%s].';

    /**
     * Fetch a collection of hydrated post objects
     *
     * @param $app \Silex\Application
     *
     * @return PostCollection
     */
    public function fetchByAuthor(\Silex\Application $app, $author_id)
    {

        $authorFactory = new AuthorFactory();
        $authorObject = $authorFactory->fetchBasics($app, $author_id);


        $dataObject = new PostData($app);
        $data = $dataObject->fetchPostCollectionData();

        $collectionObject = new PostCollection();

        if (!empty($data)) {
            foreach ($data as $record) {
                $data = new PostFactory();
                try {
                    $dataObject = $factoryObject->fetch($app, $id);
                } catch (\InvalidArgumentException $e) {
                    $app['monolog']->addError(
                        sprintf(
                            static::MESSAGE_CAUGHT_EXCEPTION,
                            $e->getMessage(),
                            $e->getCode()
                        )
                    );
                }
            }
        }

        return $collectionObject;
    }

}
