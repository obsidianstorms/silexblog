<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostHydratorTest
 *
 * Test the hydration of a complete post object
 *
 * @package BasicBlog\Post
 */
class PostHydratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test hydrate throws exception if not provided expected parameters
     */
    public function testHydrateThrowsInvalidArgumentExceptionIfObjectNotExpected()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $object = new stdClass();
        $data = "some values";

        $hydrator = new PostHydrator();
        $hydrator->hydrate($object, $data);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test hydrate hydrates expected object with provided data
     */
    public function testHydrateReturnsHydratedObjectFromProvidedData()
    {
        $expectedObject = new Post();
        $data = "some values";

        $hydrator = new PostHydrator();
        $returnedObject = $hydrator->hydrate($expectedObject, $data);

        $this->assertInstanceOf($expectedObject, $returnedObject);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test extract throws exception if not provided expected parameters
     */
    public function testExtractThrowsInvalidArgumentExceptionIfObjectNotExpected()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $object = new stdClass();

        $hydrator = new PostHydrator();
        $hydrator->hydrate($object);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test extract returns array with data pulled from provided object
     */
    public function testExtractReturnsArrayFromProvidedObject()
    {
        $expectedObject = new Post();

        $hydrator = new PostHydrator();
        $returnedValue = $hydrator->extract($expectedObject);

        $this->assertInternalType('array', $returnedValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }
}
