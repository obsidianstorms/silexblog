<?php

namespace BasicBlog\Author;

use PHPUnit_Framework_TestCase;
use Mockery as m;


/**
 * Class AuthorDataTest
 *
 * Test the author data object
 *
 * @package BasicBlog\Author
 */
class AuthorDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test doAuthorsExist() returns true if records exist
     */
    public function testDoAuthorsExistReturnsTrue()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHORS)
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $returned = $object->doAuthorsExist();

        $this->assertTrue($returned);
    }

    /**
     * Test doAuthorsExist() returns false fetch returned false
     */
    public function testDoAuthorsExistReturnsFalse()
    {
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHORS)
            ->andReturn(false);

        $mockLog = m::mock(\stdClass::class);
        $mockLog->shouldReceive('addInfo')
            ->with('Query found no authors.');

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;
        $mockApp['monolog'] = $mockLog;

        $object = new AuthorData($mockApp);
        $returned = $object->doAuthorsExist();

        $this->assertFalse($returned);
    }

    /**
     * Test doAuthorsExist() returns false if record count was zero
     */
    public function testDoAuthorsExistReturnsFalseIfZeroData()
    {
        $mockReturnedArray = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHORS)
            ->andReturn($mockReturnedArray);

        $mockLog = m::mock(\stdClass::class);
        $mockLog->shouldReceive('addInfo')
            ->with('Query found no authors.');

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;
        $mockApp['monolog'] = $mockLog;

        $object = new AuthorData($mockApp);
        $returned = $object->doAuthorsExist();

        $this->assertFalse($returned);
    }

    /**
     * Test create() throws UnexpectedValueException if sql query returns false
     */
    public function testCreateThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            AuthorData::MESSAGE_NO_RESULT_FOUND,
            0
        );

        $mockInsertData = [];
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('insert')
            ->with('authors', $mockInsertData)
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
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
            ->with('authors', $mockInsertData)
            ->andReturn(true);
        $mockDb->shouldReceive('lastInsertId')
            ->andReturn($expectedId);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $returned = $object->create($mockInsertData);

        $this->assertSame($expectedId, $returned);
    }

    /**
     * Test fetchAuthorBasicDataById() throws InvalidArgumentException if
     * provided invalid parameter
     */
    public function testFetchBasicAuthorDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            AuthorData::MESSAGE_NOT_INTEGER,
            0
        );

        $mockId = 'invalidid';
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $object = new AuthorData($mockApp);
        $object->fetchAuthorBasicDataById($mockId);
    }

    /**
     * Test fetchBasicAuthorDataById() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchBasicAuthorDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            AuthorData::MESSAGE_NO_RESULT_FOUND,
            1
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BASICS_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $object->fetchAuthorBasicDataById($mockId);
    }

    /**
     * Test fetchBasicAuthorDataById() returns array if provided with id
     */
    public function testFetchBasicAuthorDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BASICS_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $returned = $object->fetchAuthorBasicDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }


    /**
     * Test fetchAuthorDataById() throws InvalidArgumentException if
     * provided invalid parameter
     */
    public function testFetchAuthorDataByIdThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            AuthorData::MESSAGE_NOT_INTEGER,
            2
        );

        $mockId = 'invalidid';
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $object = new AuthorData($mockApp);
        $object->fetchAuthorDataById($mockId);
    }

    /**
     * Test fetchAuthorDataById() throws UnexpectedValueException if fail
     * to read from database
     */
    public function testFetchAuthorDataByIdThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            AuthorData::MESSAGE_NO_RESULT_FOUND,
            3
        );

        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_ID, [$mockId])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $object->fetchAuthorDataById($mockId);
    }

    /**
     * Test fetchAuthorDataById() returns array if provided with id
     */
    public function testFetchAuthorDataByIdReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockId = 1;
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_ID, [$mockId])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $returned = $object->fetchAuthorDataById($mockId);

        $this->assertSame($mockReturnedArray, $returned);
    }

    /**
     * Test fetchAuthorDataByEmail() throws InvalidArgumentException if
     * provided invalid parameter
     */
    public function testFetchAuthorDataByEmailThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            AuthorData::MESSAGE_NOT_EMAIL,
            5
        );

        $mockEmail = [];
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $object = new AuthorData($mockApp);
        $object->fetchAuthorDataByEmail($mockEmail);
    }

    /**
     * Test fetchAuthorDataByEmail() throws UnexpectedValueException if fail to
     * read from database
     */
    public function testFetchAuthorDataByEmailThrowsUnexpectedValueException()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            AuthorData::MESSAGE_NO_RESULT_FOUND,
            6
        );

        $mockEmail = 'samples@sample.com';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_EMAIL, [$mockEmail])
            ->andReturn(false);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $object->fetchAuthorDataByEmail($mockEmail);
    }

    /**
     * Test fetchAuthorDataByEmail() returns array if provided with id
     */
    public function testFetchAuthorDataByEmailReturnsData()
    {
        $mockReturnedArray = ['key' => 'value'];
        $mockEmail = 'sample@sample.com';
        $mockDb = m::mock(\stdClass::class);
        $mockDb->shouldReceive('fetchAssoc')
            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_EMAIL, [$mockEmail])
            ->andReturn($mockReturnedArray);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();
        $mockApp['db'] = $mockDb;

        $object = new AuthorData($mockApp);
        $returned = $object->fetchAuthorDataByEmail($mockEmail);

        $this->assertSame($mockReturnedArray, $returned);
    }
}
