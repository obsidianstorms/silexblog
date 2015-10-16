<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class PostContentDataTest
 *
 * Test the post data object
 *
 * @package BasicBlog\Post
 */
class PostContentDataTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getPostContentDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testGetPostContentDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostContentData();
        $object->getPostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test getPostContentDataById() throws UnexpectedValueException if fail to read
     * from database
     */
    public function testGetPostContentDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = 1;
        $object = new PostContentData();
        $object->getPostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test getPostContentDataById() returns array if provided with id
     */
    public function testGetPostContentDataByIdReturnsData()
    {
        $someValue = 1;
        $object = new PostContentData();
        $object->getPostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test createPostContentData() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testCreatePostContentDataThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostContentData();
        $object->createPostContentData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test savePostContentData() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testSavePostContentDataThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = ['value1', 'value2'];
        $object = new PostContentData();
        $object->createPostContentData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test createPostContentData() returns id if provided with valid data
     */
    public function testCreatePostContentDataReturnsId()
    {
        $someValue = 1;
        $object = new PostContentData();
        $object->createPostContentData($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostContentDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testUpdatePostContentDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = 'string';
        $object = new PostContentData();
        $object->updatePostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostContentDataById() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testUpdatePostContentDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = ['value1', 'value2'];
        $object = new PostContentData();
        $object->updatePostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test updatePostContentDataById() returns array if provided with valid data
     */
    public function testUpdatePostContentDataByIdSavesData()
    {
        $someValue = 1;
        $object = new PostContentData();
        $object->updatePostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostContentDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testDeletePostContentDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'A message of some kind',
            0
        );

        $someValue = [];
        $object = new PostContentData();
        $object->deletePostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostContentDataById() throws UnexpectedValueException if fail to save
     * to database
     */
    public function testDeletePostContentDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'A message of some kind',
            0
        );

        $someValue = 1;
        $object = new PostContentData();
        $object->deletePostContentDataById($someValue);

        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
    }

    /**
     * Test deletePostContentDataById() returns array if provided with valid data
     */
    public function testDeletePostContentDataByIdSavesData()
    {
        $someValue = 1;
        $object = new PostContentData();
        $object->deletePostContentDataById($someValue);

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
//        $mockPostContentData = [
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
//        $mockPostContentDataObject = m::mock(PostContentData::class);
//        $mockPostContentDataObject->shouldReceive('getPostRecordById')
//            ->with($mockId)
//            ->willReturn($mockPostContentData);
//
//        $mockPostContentDataObject = m::mock(PostContentData::class);
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
