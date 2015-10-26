<?php

namespace BasicBlog\Commentator;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class CommentatorFactoryTest
 *
 * Test the commentator data factory object
 *
 * @package BasicBlog\Commentator
 */
class CommentatorFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests the getNewCommentator() method returns new instances of
     * the CommentatorObject
     */
    public function testGetNewCommentatorReturnsNewCommentator()
    {
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $factory = new CommentatorFactory($mockApp);

        $object1 = $factory->getNewCommentator();
        $object2 = $factory->getNewCommentator();

        $this->assertInstanceOf(CommentatorData::class, $object1);
        $this->assertNotSame($object1, $object2);
    }

}


