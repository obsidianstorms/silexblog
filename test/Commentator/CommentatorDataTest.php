<?php

namespace BasicBlog\Commentator;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class CommentatorDataTest
 *
 * Test the commentator data object
 *
 * @package BasicBlog\Commentator;
 */
class CommentatorDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test create() throws UnexpectedValueException if sql query returns false
     */
    public function testCreateThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentatorData::MESSAGE_NO_RESULT_FOUND,
            0
        );

        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('commentators', $mockInsertData)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
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
            ->with('commentators', $mockInsertData)
            ->andReturn(true);
        $mockDb->shouldReceive('lastInsertId')
            ->andReturn($expectedId);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $returned = $object->create($mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test doesUsernameExist() returns false if caught error
     */
    public function testDoesUsernameExistReturnsFalseIfFailed()
    {
        $mockUsername = 'somevalue';

        $mockMonolog = m::mock(\stdClass::class);
        $mockMonolog->shouldReceive('addInfo')
            ->with('Query found no matching commentators.');

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['monolog'] = $mockMonolog;

        $partialApiObject = m::mock(CommentatorData::class, [$mockApp])->makePartial();

        $partialApiObject->shouldReceive('fetchCommentatorByUsername')
            ->with($mockUsername)
            ->andReturn(false);

        $this->assertFalse($partialApiObject->doesUsernameExist($mockUsername));
    }

    /**
     * Test doesUsernameExist() returns false if empty data
     */
    public function testDoesUsernameExistReturnsFalseIfZeroCount()
    {
        $mockUsername = 'somevalue';

        $mockReturnedData = [];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $partialApiObject = m::mock(CommentatorData::class, [$mockApp])->makePartial();

        $partialApiObject->shouldReceive('fetchCommentatorByUsername')
            ->with($mockUsername)
            ->andReturn($mockReturnedData);

        $this->assertFalse($partialApiObject->doesUsernameExist($mockUsername));
    }

    /**
     * Test doesUsernameExist() returns true if found records
     */
    public function testDoesUsernameExistReturnsTrueIfFoundRecord()
    {
        $mockUsername = 'somevalue';

        $mockReturnedData = ['key' => 'value'];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $partialApiObject = m::mock(CommentatorData::class, [$mockApp])->makePartial();

        $partialApiObject->shouldReceive('fetchCommentatorByUsername')
            ->with($mockUsername)
            ->andReturn($mockReturnedData);

        $this->assertTrue($partialApiObject->doesUsernameExist($mockUsername));
    }

    /**
     * Test fetchCommentatorBasicDataById() throws UnexpectedValueException
     * if fail to read from database
     */
    public function testCommentatorBasicDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentatorData::MESSAGE_NO_RESULT_FOUND,
            1
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BASICS_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $object->fetchCommentatorBasicDataById($mockId);
    }

    /**
     * Test fetchCommentatorBasicDataById() returns array if provided with id
     */
    public function testCommentatorBasicDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BASICS_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $returned = $object->fetchCommentatorBasicDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchCommentatorFullDataById() throws UnexpectedValueException
     * if fail to read from database
     */
    public function testCommentatorFullDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentatorData::MESSAGE_NO_RESULT_FOUND,
            2
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $object->fetchCommentatorFullDataById($mockId);
    }

    /**
     * Test fetchCommentatorFullDataById() returns array if provided with id
     */
    public function testCommentatorFullDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $returned = $object->fetchCommentatorFullDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchCommentatorByUsername() throws UnexpectedValueException
     * if fail to read from database
     */
    public function testCommentatorByUsernameThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentatorData::MESSAGE_NO_RESULT_FOUND,
            3
        );

        $mockUsername = 'somevalue';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BY_USERNAME, [$mockUsername])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $object->fetchCommentatorByUsername($mockUsername);
    }

    /**
     * Test fetchCommentatorByUsername() returns array if provided with id
     */
    public function testCommentatorByUsernameReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockUsername = 'somevalue';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(CommentatorData::SQL_SELECT_COMMENTATOR_BY_USERNAME, [$mockUsername])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $returned = $object->fetchCommentatorByUsername($mockUsername);

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test updatePassword() throws UnexpectedValueException
     * if fail to read from database
     */
    public function testUpdatePasswordThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            CommentatorData::MESSAGE_NO_RESULT_FOUND,
            4
        );

        $mockId = 1;
        $mockPassword = 'somevalue';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('commentators', ['password_hash' => $mockPassword], ['commentator_id' => $mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $object->updatePassword($mockId, $mockPassword);
    }

    /**
     * Test fetchCommentatorByUsername() returns array if provided with id
     */
    public function testUpdatePasswordReturnsDataId()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockPassword = 'somevalue';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('update')
            ->with('commentators', ['password_hash' => $mockPassword], ['commentator_id' => $mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new CommentatorData($mockApp);
        $returned = $object->updatePassword($mockId, $mockPassword);

        $this->assertSame($mockId, $returned);
    }
}
