<?php

namespace BasicBlog\Comment;


use BasicBlog\Commentator\CommentatorFactory;
use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class CommentApiTest
 *
 * Test the comment api object
 *
 * @package BasicBlog\Comment
 */
class CommentApiTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test create() method throws InvalidArgumentException if empty expected
     * fields
     */
    public function testCreateThrowsInvalidArgumentExceptionIfEmptyBody()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Body is empty.',
            2
        );

        $mockId = 1;
        $mockInsertData = [];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);

        $object = new CommentApi($mockDataObject);
        $object->create($mockId, $mockInsertData);
    }

    /**
     * Test create() method returns results if query succeeds
     */
    public function testCreateReturnsResult()
    {
        $mockPostId = 1;
        $mockCommentId = 1;
        $mockCommentatorId = 1;
        $mockInsertData = [
            'post_id' => $mockPostId,
            'body' => 'somevalue',
            'commentator_id' => $mockCommentatorId,
        ];
        $mockSessionData = ['commentator_id' => $mockCommentatorId];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('commentator')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('create')
            ->with($mockInsertData)
            ->andReturn($mockPostId);

        $object = new CommentApi($mockDataObject);
        $returned = $object->create($mockPostId, $mockInsertData);

        $this->assertSame($mockCommentId, $returned);
    }

    /**
     * Test fetchAll() method returns array
     */
    public function testFetchAllReturnsData()
    {
        $mockPostId = 1;
        $mockData = [
            'body' => 'somevalue',
        ];
        $mockList = [
            ['commentator_id' => 1,],
        ];
        $mockReturnedRecords = [
            [
                'commentator_id' => 1,
                'body' => 'somevalue',
            ],
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockCommentatorObject = m::mock(\stdClass::class);
        $mockCommentatorObject->shouldReceive('fetchCommentatorBasicDataById')
            ->with(1)
            ->andReturn($mockData);

        $mockFactoryObject = m::mock(CommentatorFactory::class, [$mockApp]);
        $mockFactoryObject->shouldReceive('getNewCommentator') //$mockCommentatorObject
            ->andReturn($mockCommentatorObject);

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchCommentsByPostId')
            ->with($mockPostId)
            ->andReturn($mockList);

        $object = new CommentApi($mockDataObject);
        $returned = $object->fetchAll($mockPostId, $mockFactoryObject);

        $this->assertSame($mockReturnedRecords, $returned);
    }

    /**
     * Test delete() method returns false if query fails
     */
    public function testDeleteReturnsFalseIfQueryFails()
    {
        $mockId = 1;

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('delete')
            ->with($mockId)
            ->andReturn(false);

        $object = new CommentApi($mockDataObject);
        $returned = $object->delete($mockId);

        $this->assertFalse($returned);
    }

    /**
     * Test delete() method returns true if query suceeds
     */
    public function testDeleteReturnsTrueIfQuerySucceeds()
    {
        $mockId = 1;

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('delete')
            ->with($mockId)
            ->andReturn(true);

        $object = new CommentApi($mockDataObject);
        $returned = $object->delete($mockId);

        $this->assertTrue($returned);
    }

    /**
     * Test deleteAllForPosts() method returns false if query fails
     */
    public function testDeleteAllForPostReturnsFalseIfQueryFails()
    {
        $mockId = 1;

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('deleteAllForPost')
            ->with($mockId)
            ->andReturn(false);

        $object = new CommentApi($mockDataObject);
        $returned = $object->deleteAllForPost($mockId);

        $this->assertFalse($returned);
    }

    /**
     * Test deleteAllForPosts() method returns true if query succeeds
     */
    public function testDeleteAllForPostReturnsTrueIfQuerySucceeds()
    {
        $mockId = 1;

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentData::class, [$mockApp]);
        $mockDataObject->shouldReceive('deleteAllForPost')
            ->with($mockId)
            ->andReturn(true);

        $object = new CommentApi($mockDataObject);
        $returned = $object->deleteAllForPost($mockId);

        $this->assertTrue($returned);
    }
}
