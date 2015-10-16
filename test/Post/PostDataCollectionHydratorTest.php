<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostDataCollectionTest
 *
 * Test the collection of post data objects
 *
 * @package BasicBlog\Post
 */
class PostDataCollectionHydratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test hydrate throws exception if not provided expected parameters
     */
    public function testHydrateThrowsInvalidArgumentExceptionIfObjectsNotExpected()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $object = new stdClass();
        $data = "some values";

        $hydrator = new PostCollectionHydrator();
        $hydrator->hydrate($object, $data);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test hydrate hydrates expected object with provided data
     */
    public function testHydrateReturnsHydratedObjectArrayFromProvidedData()
    {
        $expectedObject = new PostCollection();
        $data = "some values";

        $hydrator = new PostCollectionHydrator();
        $returnedObject = $hydrator->hydrate($expectedObject, $data);

        $this->assertInstanceOf($expectedObject, $returnedObject);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

}