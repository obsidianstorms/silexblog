<?php

namespace BasicBlog\Comment;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class CommentDataTest
 *
 * Test the comment data object
 *
 * @package BasicBlog\Comment
 */
class CommentDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test create() throws UnexpectedValueException if sql query returns false
     */
    public function testCreateThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentData::MESSAGE_NO_RESULT_FOUND,
            0
        );

        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('comments', $mockInsertData)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
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
            ->with('comments', $mockInsertData)
            ->andReturn(true);
        $mockDb->shouldReceive('lastInsertId')
            ->andReturn($expectedId);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $returned = $object->create($mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test fetchCommentsByPostId() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchCommentsByPostIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentData::MESSAGE_NO_RESULT_FOUND,
            2
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(CommentData::SQL_SELECT_COMMENTS_OF_POST_SORTED_CREATED_ASC, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $object->fetchCommentsByPostId($mockId);
    }

    /**
     * Test fetchCommentsByPostId() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchCommentsByPostIdReturnsRecord()
    {
        $mockRecord = [];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAll')
            ->with(CommentData::SQL_SELECT_COMMENTS_OF_POST_SORTED_CREATED_ASC, [$mockId])
            ->andReturn($mockRecord);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $returned = $object->fetchCommentsByPostId($mockId);

        $this->assertSame($mockRecord, $returned);
    }

    /**
     * Test delete() throws UnexpectedValueException if sql query returns false
     */
    public function testDeleteThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentData::MESSAGE_NO_RESULT_FOUND,
            9
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('comments', ['comment_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
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
            ->with('comments', ['comment_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $returned = $object->delete($mockId);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test deleteAllForPost() throws UnexpectedValueException if sql query returns false
     */
    public function testDeleteAllForPostThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentData::MESSAGE_NO_RESULT_FOUND,
            10
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('comments', ['post_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $object->deleteAllForPost($mockId);
    }

    /**
     * Test deleteAllForPost() returns id if sql insert succeeds
     */
    public function testDeleteAllForPostReturnsId()
    {
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('delete')
            ->with('comments', ['post_id' => $mockId])
            ->andReturn(true);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentData($mockApp);
        $returned = $object->deleteAllForPost($mockId);

        $this->assertSame($mockId, $returned);
    }
}
