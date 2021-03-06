<?php

namespace BasicBlog\Post;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class PostApiTest
 *
 * Test the post api object
 *
 * @package BasicBlog\Post
 */
class PostApiTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test create() method throws InvalidArgumentException if empty expected
     * fields
     */
    public function testCreateThrowsInvalidArgumentExceptionIfEmptyTitle()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Title is empty.',
            1
        );

        $mockInsertData = [];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);

        $object = new PostApi($mockDataObject);
        $object->create($mockInsertData);
    }

    /**
     * Test create() method throws InvalidArgumentException if author session
     * not exist
     */
    public function testCreateThrowsInvalidArgumentExceptionIfAuthorNotLoggedIn()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Author is not logged in.',
            2
        );

        $mockInsertData = ['title' => 'value'];
        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn(null);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new PostApi($mockDataObject);
        $object->create($mockInsertData);
    }

    /**
     * Test create() method returns false if query fails
     */
    public function testCreateReturnsFalseIfPostQueryFails()
    {
        $mockInsertData = [
            'title' => 'somevalue',
            'body' => ''
        ];
        $mockSessionData = ['author_id' => 1];
        $mockInsertPostData = [
            'author_id' => 1,
            'title' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('create')
            ->with($mockInsertPostData)
            ->andReturn(false);

        $object = new PostApi($mockDataObject);
        $returned = $object->create($mockInsertData);

        $this->assertFalse($returned);
    }


    /**
     * Test create() method returns false if query fails
     */
    public function testCreateReturnsFalseIfPostContentQueryFails()
    {
        $mockPostId = 1;
        $mockInsertData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockInsertPostData = [
            'author_id' => 1,
            'title' => 'somevalue',
        ];
        $mockInsertContentData = [
            'post_id' => $mockPostId,
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('create')
            ->with($mockInsertPostData)
            ->andReturn($mockPostId);
        $mockDataObject->shouldReceive('createContent')
            ->with($mockInsertContentData)
            ->andReturn(false);

        $object = new PostApi($mockDataObject);
        $returned = $object->create($mockInsertData);

        $this->assertFalse($returned);
    }

    /**
     * Test create() method returns true if query succeeds
     */
    public function testCreateReturnsPostIdIfQuerySucceeds()
    {
        $mockPostId = 1;
        $mockInsertData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockInsertPostData = [
            'author_id' => 1,
            'title' => 'somevalue',
        ];
        $mockInsertContentData = [
            'post_id' => $mockPostId,
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('create')
            ->with($mockInsertPostData)
            ->andReturn($mockPostId);
        $mockDataObject->shouldReceive('createContent')
            ->with($mockInsertContentData)
            ->andReturn(true);

        $object = new PostApi($mockDataObject);
        $returned = $object->create($mockInsertData);

        $this->assertSame($mockPostId, $returned);
    }

    /**
     * Test update() method throws InvalidArgumentException if empty expected
     * fields
     */
    public function testUpdateThrowsInvalidArgumentExceptionIfEmptyTitle()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Title is empty.',
            3
        );

        $mockPostId = 1;
        $mockData = [];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);

        $object = new PostApi($mockDataObject);
        $object->update($mockPostId, $mockData);
    }

    /**
     * Test update() method throws InvalidArgumentException no author session
     * exists
     */
    public function testUpdateThrowsInvalidArgumentExceptionIfAuthorNotLoggedIn()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Author is not logged in.',
            4
        );

        $mockPostId = 1;
        $mockData = ['title' => 'value'];
        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn(null);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new PostApi($mockDataObject);
        $object->update($mockPostId, $mockData);
    }

    /**
     * Test update() method returns false if query fails
     */
    public function testUpdateReturnsFalseIfPostQueryFails()
    {
        $mockPostId = 1;
        $mockData = [
            'title' => 'somevalue',
            'body' => ''
        ];
        $mockSessionData = ['author_id' => 1];
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => '',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('update')
            ->with($mockPostId, $mockPostData)
            ->andReturn(false);
        $mockDataObject->shouldReceive('updateContent')
            ->with($mockPostId, $mockContentData)
            ->andReturn(true);

        $object = new PostApi($mockDataObject);
        $returned = $object->update($mockPostId, $mockData);

        $this->assertFalse($returned);
    }

    /**
     * Test update() method returns false if query fails
     */
    public function testUpdateReturnsFalseIfPostContentQueryFails()
    {
        $mockPostId = 1;
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('update')
            ->with($mockPostId, $mockPostData);
        $mockDataObject->shouldReceive('updateContent')
            ->with($mockPostId, $mockContentData)
            ->andReturn(false);

        $object = new PostApi($mockDataObject);
        $returned = $object->update($mockPostId, $mockData);

        $this->assertFalse($returned);
    }

    /**
     * Test update() method returns post id if query succeeds
     */
    public function testUpdateReturnsPostIdIfQuerySucceeds()
    {
        $mockPostId = 1;
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('update')
            ->with($mockPostId, $mockPostData)
            ->andReturn($mockPostId);
        $mockDataObject->shouldReceive('updateContent')
            ->with($mockPostId, $mockContentData)
            ->andReturn(true);

        $object = new PostApi($mockDataObject);
        $returned = $object->update($mockPostId, $mockData);

        $this->assertSame($mockPostId, $returned);
    }

    /**
     * Test fetchAll() method returns array
     */
    public function testFetchAllReturnsData()
    {
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];

        $mockApp = m::mock(\Silex\Application::class)
        ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchPosts')
            ->andReturn($mockData);

        $object = new PostApi($mockDataObject);
        $returned = $object->fetchAll();

        $this->assertSame($mockData, $returned);
    }

    /**
     * Test fetchA() method returns array if provided id
     */
    public function testFetchReturnsDataIfProvidedId()
    {
        $mockId = 1;
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => 'somevalue',
        ];
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchPostDataById')
            ->with($mockId)
            ->andReturn($mockPostData);
        $mockDataObject->shouldReceive('fetchPostContentDataById')
            ->with($mockId)
            ->andReturn($mockContentData);

        $object = new PostApi($mockDataObject);
        $returned = $object->fetch($mockId);

        $this->assertSame($mockData, $returned);
    }

    /**
     * Test delete() returns false if query fails
     */
    public function testDeleteReturnsFalseIfQueryFails()
    {
        $mockPostId = 1;
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('update')
            ->with($mockPostId, $mockPostData);
        $mockDataObject->shouldReceive('updateContent')
            ->with($mockPostId, $mockContentData)
            ->andReturn(false);

        $object = new PostApi($mockDataObject);
        $returned = $object->update($mockPostId, $mockData);

        $this->assertFalse($returned);
    }

    /**
     * Test delete() returns post id if query succeeds
     */
    public function testDeleteReturnsPostIdIfQuerySucceeds()
    {
        $mockPostId = 1;
        $mockData = [
            'title' => 'somevalue',
            'body' => 'somevalue'
        ];
        $mockSessionData = ['author_id' => 1];
        $mockPostData = [
            'title' => 'somevalue',
        ];
        $mockContentData = [
            'body' => 'somevalue',
        ];

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('get')
            ->with('author')
            ->andReturn($mockSessionData);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(PostData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);
        $mockDataObject->shouldReceive('update')
            ->with($mockPostId, $mockPostData)
            ->andReturn($mockPostId);
        $mockDataObject->shouldReceive('updateContent')
            ->with($mockPostId, $mockContentData)
            ->andReturn(true);

        $object = new PostApi($mockDataObject);
        $returned = $object->update($mockPostId, $mockData);

        $this->assertSame($mockPostId, $returned);
    }
}
