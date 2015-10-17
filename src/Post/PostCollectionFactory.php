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
     * Fetch a hydrated post object
     *
     * @param $app \Silex\Application
     *
     * @return PostCollection
     */
    public function fetch(\Silex\Application $app)
    {
        $dataObject = new PostData($app);
        $data = $dataObject->fetchPostCollectionData();

        $collectionObject = new PostCollection();

        if (!empty($data)) {
            foreach ($data as $record) {
                $postHydrator = new PostHydrator();
                $postHydrator->setApp($app);
                try {
                    $postObject = $postHydrator->hydrateReference(new Post(), $record);
                    $collectionObject->addToCollection($postObject);
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
