<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostTest
 *
 * Test the representation of a complete post object
 *
 * @package BasicBlog\Post
 */
class PostTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $object;

    /**
     * Setup the object to be tested
     */
    protected function setUp()
    {
        $this->object = new Post();
    }

    /**
     * Test getter and setters for post id
     */
    public function testPostIdGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getPostId(),
            'Post id property was incorrectly populated at instantiation.'
        );

        $expectedValue = 1;
        $this->assertSame(
            $this->object,
            $this->object->setPostId($expectedValue),
            'Post id setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getPostId(),
            'Post id property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for post id
     */
    public function testPostIdSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setPostId('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }

    /**
     * Test getter and setters for author id
     */
    public function testAuthorIdGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getAuthorId(),
            'Author id property was incorrectly populated at instantiation.'
        );

        $expectedValue = 1;
        $this->assertSame(
            $this->object,
            $this->object->setAuthorId($expectedValue),
            'Author id setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getAuthorId(),
            'Author id property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for author id
     */
    public function testAuthorIdSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setAuthorId('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }

    /**
     * Test getter and setters for title
     */
    public function testTitleGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getTitle(),
            'Title property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'Some title';
        $this->assertSame(
            $this->object,
            $this->object->setTitle($expectedValue),
            'Title setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getTitle(),
            'Title property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for title
     */
    public function testTitleSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setTitle('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }

    /**
     * Test getter and setters for body
     */
    public function testBodyGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getBody(),
            'Body property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'Some body';
        $this->assertSame(
            $this->object,
            $this->object->setBody($expectedValue),
            'Body setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getBody(),
            'Body property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for body
     */
    public function testBodySetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setBody('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }

    /**
     * Test getter and setters for created datetime
     */
    public function testCreatedGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getCreated(),
            'Created property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'datetime';
        $this->assertSame(
            $this->object,
            $this->object->setCreated($expectedValue),
            'Created setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getCreated(),
            'Created property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for created
     */
    public function testCreatedSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setCreated('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }

    /**
     * Test getter and setters for updated datetime
     */
    public function testUpdatedGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getUpdated(),
            'Updated property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'datetime';
        $this->assertSame(
            $this->object,
            $this->object->setUpdated($expectedValue),
            'Updated setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getUpdated(),
            'Updated property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for updated
     */
    public function testUpdatedSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $this->object->setUpdated('invalid value');

        $this->markTestIncomplete('This test needs: dataProvider, value expectations, message and code');
    }
}
