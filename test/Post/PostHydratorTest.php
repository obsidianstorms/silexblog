<?php

namespace BasicBlog\Post;

use BasicBlog\Author\Author;
use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostHydratorTest
 *
 * Test the hydration of a post object
 *
 * @package BasicBlog\Post
 */
class PostHydratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test hydrate throws exception if not provided expected parameters
     */
    public function testHydrateThrowsInvalidArgumentExceptionIfEmptyArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostHydrator::MESSAGE_INVALID_ARRAY,
            0
        );

        $object = new Post();
        $data = [];

        $hydrator = new PostHydrator();
        $hydrator->hydrate($object, $data);
    }

    /**
     * Test hydrate throws exception if missing expected data key
     */
    public function testHydrateThrowsInvalidArgumentExceptionIfMissingKey()
    {
        $mockExpectedKey = 'body';
        $this->setExpectedException(
            'InvalidArgumentException',
            sprintf(
                PostHydrator::MESSAGE_EXPECTED_KEY_NOT_FOUND,
                $mockExpectedKey
            ),
            1
        );

        $object = new Post();
        $data = [ //missing 'body' key
            'post_id' => 1,
            'author_id' => 1,
            'title' => 'value',
            'created' => 'value',
            'updated' => 'value',
        ];

        $hydrator = new PostHydrator();
        $hydrator->hydrate($object, $data);
    }

    /**
     * Test hydrate returns expected object if
     */
    public function testHydrateReturnsObject()
    {
        $expectedObject = new Post();
        $data = [
            'post_id' => 1,
            'author_id' => 1,
            'title' => 'value',
            'created' => 'value',
            'updated' => 'value',
            'body' => 'some value',
        ];

        $hydrator = new PostHydrator();
        $returnedObject = $hydrator->hydrate($expectedObject, $data);

        $this->assertInstanceOf(Post::class, $returnedObject);
    }

    /**
     * Test hydrateReference throws exception if not provided expected parameters
     */
    public function testHydrateReferenceThrowsInvalidArgumentExceptionIfEmptyArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostHydrator::MESSAGE_INVALID_ARRAY,
            2
        );

        $object = new Post();
        $data = [];

        $hydrator = new PostHydrator();
        $hydrator->hydrateReference($object, $data);
    }

    /**
     * Test hydrateReference throws exception if missing expected data key
     */
    public function testHydrateReferenceThrowsInvalidArgumentExceptionIfMissingKey()
    {
        $mockExpectedKey = 'post_id';
        $this->setExpectedException(
            'InvalidArgumentException',
            sprintf(
                PostHydrator::MESSAGE_EXPECTED_KEY_NOT_FOUND,
                $mockExpectedKey
            ),
            3
        );

        $object = new Post();
        $data = [
            'author_id' => 1,
            'title' => 'value',
            'created' => 'value',
            'updated' => 'value',
        ];

        $hydrator = new PostHydrator();
        $hydrator->hydrateReference($object, $data);
    }

    /**
     * Test hydrateReference returns expected object
     */
    public function testHydrateReferenceReturnsObject()
    {
        $expectedObject = new Post();
        $data = [
            'post_id' => 1,
            'author_id' => 1,
            'title' => 'value',
            'created' => 'value',
            'updated' => 'value',
        ];

        $hydrator = new PostHydrator();
        $returnedObject = $hydrator->hydrateReference($expectedObject, $data);

        $this->assertInstanceOf(Post::class, $returnedObject);
    }

    /**
     * Test setApp() method
     */
    public function testSetApp()
    {
        $hydrator = new PostHydrator();

        $this->assertNull(
            $hydrator->getApp(),
            'App property was incorrectly populated at instantiation.'
        );

        $expectedValue = m::mock(\Silex\Application::class)
            ->makePartial();
        $this->assertSame(
            $hydrator,
            $hydrator->setApp($expectedValue),
            'App setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $hydrator->getApp(),
            'App property did not return expected value.'
        );
    }
}
