<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PostCollectionFactoryTest
 *
 * Test the factory of a post collection
 *
 * @package BasicBlog\Post
 */
class PostCollectionFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the fetch() methodf
     */
    public function testFetchMethodReturnsPostCollectionObject()
    {
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll');

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostCollectionFactory();
        $returned = $object->fetch($mockApp);

        $this->assertInstanceOf(PostCollection::class, $returned);
    }
}
