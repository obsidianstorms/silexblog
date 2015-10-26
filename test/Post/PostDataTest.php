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
     * Test create() throws UnexpectedValueException if sql query returns false
     */
    public function testCreateThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            0
        );

        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('posts', $mockInsertData)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->create($mockInsertData);
    }

    /**
     * Test create() returns id if sql insert succeeds
     */
    public function testCreateReturnsId()
    {
        $expectedId = 1;
        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('posts', $mockInsertData)
            ->andReturn(true);
        $mockDb->shouldReceive('lastInsertId')
            ->andReturn($expectedId);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->create($mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test createContent() throws UnexpectedValueException if sql query
     * returns false
     */
    public function testCreateContentThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            1
        );

        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('post_content', $mockInsertData)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->createContent($mockInsertData);
    }

    /**
     * Test createContent() returns id if sql insert succeeds
     */
    public function testCreateContentReturnsId()
    {
        $expectedId = 1;
        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('post_content', $mockInsertData)
            ->andReturn(true);
        $mockDb->shouldReceive('lastInsertId')
            ->andReturn($expectedId);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->createContent($mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test update() throws UnexpectedValueException if sql query
     * returns false
     */
    public function testUpdateThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            2
        );

        $mockId = 1;
        $mockInsertData = [];
        $timestamp = new \DateTime('now');
        $mockInsertData['updated'] = $timestamp->format('Y-m-d H:i:s');

        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('posts', $mockInsertData, ['post_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->update($mockId, $mockInsertData);
    }

    /**
     * Test update() returns id if sql insert succeeds
     */
    public function testUpdateReturnsId()
    {
        $mockId = 1;
        $mockInsertData = [];
        $timestamp = new \DateTime('now');
        $mockInsertData['updated'] = $timestamp->format('Y-m-d H:i:s');

        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('posts', $mockInsertData, ['post_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->update($mockId, $mockInsertData);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test updateContent() throws UnexpectedValueException if sql query
     * returns false
     */
    public function testUpdateContentThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            3
        );

        $mockId = 1;
        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('post_content', $mockInsertData, ['post_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->updateContent($mockId, $mockInsertData);
    }

    /**
     * Test updateContent() returns id if sql insert succeeds
     */
    public function testUpdateContentReturnsId()
    {
        $mockId = 1;
        $expectedId = 1;
        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('post_content', $mockInsertData, ['post_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->updateContent($mockId, $mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test delete() throws UnexpectedValueException if sql query returns false
     */
    public function testDeleteThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            9
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('posts', ['post_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->delete($mockId);
    }

    /**
     * Test delete() returns id if sql insert succeeds
     */
    public function testDeleteReturnsId()
    {
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('posts', ['post_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->delete($mockId);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test deleteContent() throws UnexpectedValueException if sql query
     * returns false
     */
    public function testDeleteContentThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            10
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('post_content', ['post_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->deleteContent($mockId);
    }

    /**
     * Test deleteContent() returns id if sql insert succeeds
     */
    public function testDeleteContentReturnsId()
    {
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('post_content', ['post_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->deleteContent($mockId);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test fetchPosts() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchPostsThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            PostData::MESSAGE_NO_RESULT_FOUND,
            4
        );

        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(PostData::SQL_SELECT_POSTS_SORTED_CREATED_ASC)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $object->fetchPosts();
    }

    /**
     * Test fetchPosts() returns array
     */
    public function testFetchPostsReturnsData()
    {
        $mockReturnedArray = [
            ['key' => 'value'],
            ['key2' => 'value2'],
        ];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(PostData::SQL_SELECT_POSTS_SORTED_CREATED_ASC)
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new PostData($mockApp);
        $returned = $object->fetchPosts();

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchPostDataById() throws InvalidArgumentException if provided
     * invalid parameter
     */
    public function testFetchPostDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostData::MESSAGE_NOT_INTEGER,
            5
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
            6
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
     * Test fetchPostContentDataById() throws InvalidArgumentException if
     * provided invalid parameter
     */
    public function testFetchPostContentDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            PostData::MESSAGE_NOT_INTEGER,
            7
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
            8
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
}
