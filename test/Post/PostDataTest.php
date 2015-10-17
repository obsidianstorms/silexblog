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
     * Test fetchPostDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testFetchPostDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostData::MESSAGE_NOT_INTEGER,
            0
        );

        $mockId = 'invalidid';
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $object = new PostData($mockApp);
        $object->fetchPostDataById($mockId);
    }

    /**
     * Test fetchPostDataById() throws UnexpectedValueException if fail to read
     * from database
     */
    public function testFetchPostDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            1
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(PostData::SQL_SELECT_SINGLE_POST_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->fetchPostDataById($mockId);
    }

    /**
     * Test fetchPostDataById() returns array if provided with id
     */
    public function testFetchPostDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(PostData::SQL_SELECT_SINGLE_POST_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->fetchPostDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchPostCollectionData() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchPostCollectionDataThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            2
        );

        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(PostData::SQL_SELECT_ALL_POSTS_SORTED_CREATED_ASC)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->fetchPostCollectionData();
    }

    /**
     * Test fetchPostCollectionData() returns array
     */
    public function testFetchPostCollectionDataReturnsData()
    {
        $mockReturnedArray = [
            ['key' => 'value'],
            ['key2' => 'value2'],
        ];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(PostData::SQL_SELECT_ALL_POSTS_SORTED_CREATED_ASC)
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->fetchPostCollectionData();

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchPostContentDataById() throws InvalidArgumentException if
     * provided invalid parameter
     */
    public function testFetchPostContentDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostData::MESSAGE_NOT_INTEGER,
            3
        );

        $mockId = 'invalidid';
        $mockApp = m::mock(\Silex\Application::class);

        $object = new PostData($mockApp);
        $object->fetchPostContentDataById($mockId);
    }

    /**
     * Test fetchPostContentDataById() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchPostContentDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            4
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(PostData::SQL_SELECT_POST_CONTENT_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->fetchPostContentDataById($mockId);
    }

    /**
     * Test fetchPostContentDataById() returns array if provided with id
     */
    public function testFetchPostContentDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(PostData::SQL_SELECT_POST_CONTENT_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->fetchPostContentDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }


















//
//    /**
//     * Test createPostData() throws InvalidArgumentException if provided
//     * invalid parameter
//     */
//    public function testCreatePostDataThrowsInvalidArgumentException()
//    {
//        $this->setExpectedException(
//            'InvalidArgumentException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = 'string';
//        $object = new PostData();
//        $object->createPostData($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test savePostData() throws UnexpectedValueException if fail to save
//     * to database
//     */
//    public function testSavePostDataThrowsUnexpectedValueException()
//    {
//        $this->setExpectedException(
//            'UnexpectedValueException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = ['value1', 'value2'];
//        $object = new PostData();
//        $object->createPostData($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test createPostData() returns id if provided with valid data
//     */
//    public function testCreatePostDataReturnsId()
//    {
//        $someValue = 1;
//        $object = new PostData();
//        $object->createPostData($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test updatePostDataById() throws InvalidArgumentException if provided
//     * invalid parameter
//     */
//    public function testUpdatePostDataByIdThrowsInvalidArgumentException()
//    {
//        $this->setExpectedException(
//            'InvalidArgumentException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = 'string';
//        $object = new PostData();
//        $object->updatePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test updatePostDataById() throws UnexpectedValueException if fail to save
//     * to database
//     */
//    public function testUpdatePostDataByIdThrowsUnexpectedValueException()
//    {
//        $this->setExpectedException(
//            'UnexpectedValueException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = ['value1', 'value2'];
//        $object = new PostData();
//        $object->updatePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test updatePostDataById() returns array if provided with valid data
//     */
//    public function testUpdatePostDataByIdSavesData()
//    {
//        $someValue = 1;
//        $object = new PostData();
//        $object->updatePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test deletePostDataById() throws InvalidArgumentException if provided
//     * invalid parameter
//     */
//    public function testDeletePostDataByIdThrowsInvalidArgumentException()
//    {
//        $this->setExpectedException(
//            'InvalidArgumentException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = [];
//        $object = new PostData();
//        $object->deletePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test deletePostDataById() throws UnexpectedValueException if fail to save
//     * to database
//     */
//    public function testDeletePostDataByIdThrowsUnexpectedValueException()
//    {
//        $this->setExpectedException(
//            'UnexpectedValueException',
//            'A message of some kind',
//            0
//        );
//
//        $someValue = 1;
//        $object = new PostData();
//        $object->deletePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }
//
//    /**
//     * Test deletePostDataById() returns array if provided with valid data
//     */
//    public function testDeletePostDataByIdSavesData()
//    {
//        $someValue = 1;
//        $object = new PostData();
//        $object->deletePostDataById($someValue);
//
//        $this->markTestIncomplete('This test may need: dataProvider, value expectations, message and code');
//    }








    /**
     * Test fetchPostById() returns array if provided with id
     */
//    public function testFetchPostById()
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
//        $mockPostDataObject->shouldReceive('fetchPostRecordById')
//            ->with($mockId)
//            ->willReturn($mockPostData);
//
//        $mockPostContentDataObject = m::mock(PostData::class);
//        $mockPostContentDataObject->shouldReceive('fetchPostContentRecordById')
//            ->with($mockId)
//            ->willReturn($mockPostContentData);
////there needs to be a hydrator here somewhere....
//        $object = new Post();
//        $array = $object->fetchPostById($mockId);
//
//        $this->assertSame($mockReturnData, $array);
//    }

}
