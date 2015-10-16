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
class PostDataCollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test getPostDataList() returns array if provided with id
     */
    public function testGetPostDataListReturnsData()
    {
        $someValue = 1;
        $object = new PostData();
        $object->getPostDataList($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }
    /**
     * Test getPostDataList() throws UnexpectedValueException if fail to read
     * from database
     */
    public function testGetPostDataListThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = 1;
        $object = new PostData();
        $object->getPostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }
}
