<?php
//
//namespace BasicBlog\Author;
//
//use PHPUnit_Framework_TestCase;
//use Mockery as m;
//
//
///**
// * Class AuthorDataTest
// *
// * Test the author data object
// *
// * @package BasicBlog\Author
// */
//class AuthorDataTest extends PHPUnit_Framework_TestCase
//{
//    /**
//     * Test fetchAuthorDataById() throws InvalidArgumentException if provided
//     * invalid parameter
//     */
//    public function testFetchAuthorDataByIdThrowsInvalidArgumentException()
//    {
//        $this->setExpectedException(
//            'InvalidArgumentException',
//            AuthorData::MESSAGE_NOT_INTEGER,
//            0
//        );
//
//        $mockId = 'invalidid';
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//
//        $object = new AuthorData($mockApp);
//        $object->fetchAuthorDataById($mockId);
//    }
//
//    /**
//     * Test fetchAuthorDataById() throws UnexpectedValueException if fail to read
//     * from database
//     */
//    public function testFetchAuthorDataByIdThrowsUnexpectedValueException()
//    {
//        $this->setExpectedException(
//            'UnexpectedValueException',
//            AuthorData::MESSAGE_NO_RESULT_FOUND,
//            1
//        );
//
//        $mockId = 1;
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_ID, [$mockId])
//            ->andReturn(false);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $object->fetchAuthorDataById($mockId);
//    }
//
//    /**
//     * Test fetchAuthorDataById() returns array if provided with id
//     */
//    public function testFetchAuthorDataByIdReturnsData()
//    {
//        $mockReturnedArray = ['key' => 'value'];
//        $mockId = 1;
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_ID, [$mockId])
//            ->andReturn($mockReturnedArray);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $returned = $object->fetchAuthorDataById($mockId);
//
//        $this->assertSame($mockReturnedArray, $returned);
//    }
//
//    /**
//     * Test fetchAuthorDataByEmail() throws InvalidArgumentException if
//     * provided invalid parameter
//     */
//    public function testFetchAuthorDataByEmailThrowsInvalidArgumentException()
//    {
//        $this->setExpectedException(
//            'InvalidArgumentException',
//            AuthorData::MESSAGE_NOT_EMAIL,
//            2
//        );
//
//        $mockEmail = [];
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//
//        $object = new AuthorData($mockApp);
//        $object->fetchAuthorDataByEmail($mockEmail);
//    }
//
//    /**
//     * Test fetchAuthorDataByEmail() throws UnexpectedValueException if fail to
//     * read from database
//     */
//    public function testFetchAuthorDataByEmailThrowsUnexpectedValueException()
//    {
//        $this->setExpectedException(
//            'UnexpectedValueException',
//            AuthorData::MESSAGE_NO_RESULT_FOUND,
//            3
//        );
//
//        $mockEmail = 'samples@sample.com';
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_EMAIL, [$mockEmail])
//            ->andReturn(false);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $object->fetchAuthorDataByEmail($mockEmail);
//    }
//
//    /**
//     * Test fetchAuthorDataByEmail() returns array if provided with id
//     */
//    public function testFetchAuthorDataByEmailReturnsData()
//    {
//        $mockReturnedArray = ['key' => 'value'];
//        $mockEmail = 'sample@sample.com';
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHOR_BY_EMAIL, [$mockEmail])
//            ->andReturn($mockReturnedArray);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $returned = $object->fetchAuthorDataByEmail($mockEmail);
//
//        $this->assertSame($mockReturnedArray, $returned);
//    }
//
//    /**
//     * Test doAuthorsExist() returns true if records exist
//     */
//    public function testDoAuthorsExistReturnsTrue()
//    {
//        $mockReturnedArray = ['key' => 'value'];
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHORS)
//            ->andReturn($mockReturnedArray);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $returned = $object->doAuthorsExist();
//
//        $this->assertTrue($returned);
//    }
//
//    /**
//     * Test doAuthorsExist() returns true if records do not exist
//     */
//    public function testDoAuthorsExistReturnsFalse()
//    {
//        $mockDb = m::mock(\stdClass::class);
//        $mockDb->shouldReceive('fetchAssoc')
//            ->with(AuthorData::SQL_SELECT_AUTHORS)
//            ->andReturn(false);
//
//        $mockApp = m::mock(\Silex\Application::class)
//            ->makePartial();
//        $mockApp['db'] = $mockDb;
//
//        $object = new AuthorData($mockApp);
//        $returned = $object->doAuthorsExist();
//
//        $this->assertFalse($returned);
//    }
//
//}
