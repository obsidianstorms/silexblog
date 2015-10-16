<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class PostDataTest
 *
 * Test the post data object
 *
 * @package BasicBlog\Post
 */
class PostDataTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getPostDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testGetPostDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostData();
        $object->getPostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test getPostDataById() throws UnexpectedValueException if fail to read
     * from database
     */
    public function testGetPostDataByIdThrowsUnexpectedValueException()
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

    /**
     * Test getPostDataById() returns array if provided with id
     */
    public function testGetPostDataByIdReturnsData()
    {
        $someValue = 1;
        $object = new PostData();
        $object->getPostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test createPostData() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testCreatePostDataThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostData();
        $object->createPostData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test savePostData() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testSavePostDataThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = ['value1', 'value2'];
        $object = new PostData();
        $object->createPostData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test createPostData() returns id if provided with valid data
     */
    public function testCreatePostDataReturnsId()
    {
        $someValue = 1;
        $object = new PostData();
        $object->createPostData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testUpdatePostDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostData();
        $object->updatePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostDataById() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testUpdatePostDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = ['value1', 'value2'];
        $object = new PostData();
        $object->updatePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostDataById() returns array if provided with valid data
     */
    public function testUpdatePostDataByIdSavesData()
    {
        $someValue = 1;
        $object = new PostData();
        $object->updatePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testDeletePostDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = [];
        $object = new PostData();
        $object->deletePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostDataById() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testDeletePostDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = 1;
        $object = new PostData();
        $object->deletePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostDataById() returns array if provided with valid data
     */
    public function testDeletePostDataByIdSavesData()
    {
        $someValue = 1;
        $object = new PostData();
        $object->deletePostDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }








    /**
     * Test getPostById() returns array if provided with id
     */
//    public function testGetPostById()
//    {
//        $mockId = 1;
//
//        $mockReturnData = [
//            'post_id' => 1,
//            'author_id' => 1,
//            'title' => 'Some title',
//            'body' => 'Some body',
//            'created' => 'datetime',
//            'updated' => 'datetime2',
//        ];
//
//        $mockPostData = [
//            'post_id' => 1,
//            'author_id' => 1,
//            'title' => 'Some title',
//            'created' => 'datetime',
//            'updated' => 'datetime2',
//        ];
//
//        $mockPostContentData = [
//            'content_id' => 1,
//            'post_id' => 1,
//            'body' => 'Some body',
//        ];
//
//        $mockPostDataObject = m::mock(PostData::class);
//        $mockPostDataObject->shouldReceive('getPostRecordById')
//            ->with($mockId)
//            ->willReturn($mockPostData);
//
//        $mockPostContentDataObject = m::mock(PostData::class);
//        $mockPostContentDataObject->shouldReceive('getPostContentRecordById')
//            ->with($mockId)
//            ->willReturn($mockPostContentData);
////there needs to be a hydrator here somewhere....
//        $object = new Post();
//        $array = $object->getPostById($mockId);
//
//        $this->assertSame($mockReturnData, $array);
//    }

}
