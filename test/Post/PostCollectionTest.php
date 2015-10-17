<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostCollectionTest
 *
 * Test the collection of post data objects
 *
 * @package BasicBlog\Post
 */
class PostCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PostCollection
     */
    protected $object;

    /**
     * Setup the object to be tested
     */
    protected function setUp()
    {
        $this->object = new PostCollection();
    }

    /**
     * Test getter and setter(addition) of colleciton property
     */
    public function testPostCollectionGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getCollection(),
            'Collection property was incorrectly populated at instantiation.'
        );

        $mockPost = m::mock(Post::class);
        $this->assertSame(
            $this->object,
            $this->object->addToCollection($mockPost),
            'The addToCollection method did not return static object.'
        );

        $this->assertSame(
            [$mockPost],
            $this->object->getCollection(),
            'The getCollection method did not return expected array.'
        );
    }
}
