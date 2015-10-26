<?php

namespace BasicBlog\Author;

use BasicBlog\Security\Password;
use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class AuthorApiTest
 *
 * Test the author api object
 *
 * @package BasicBlog\Author
 */
class AuthorApiTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test create() method returns false if any authors exist
     */
    public function testCreateReturnsFalseIfAnyAuthorsExists()
    {
        $mockEmail = 'sample@email.com';
        $mockInsertData = [
            'email_address' => $mockEmail,
            'password' => 'somepassword',
            'password_confirm' => 'somepassword',

        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('doAuthorsExist')
            ->andReturn(true);

        $object = new AuthorApi($mockDataObject);
        $returned = $object->create($mockInsertData);

        $this->assertFalse($returned);
    }

    /**
     * Test create() method throws InvalidArgumentException if password fields
     * don't match
     */
    public function testCreateThrowsInvalidArgumentExceptionIfPasswordConfirmationFails()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Password fields did not match.',
            1
        );

        $mockEmail = 'sample@email.com';
        $mockInsertData = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'email_address' => $mockEmail,
            'password' => 'somepassword',
            'password_confirm' => 'someotherpassword',

        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('doAuthorsExist')
            ->andReturn(false);

        $object = new AuthorApi($mockDataObject);
        $object->create($mockInsertData);
    }

    /**
     * Test create() returns id value if query succeeds
     */
    public function testCreateReturnsIdIfSuccessful()
    {
        $mockId = 1;
        $mockEmail = 'sample@email.com';
        $mockPassword = 'somepassword';
        $mockProvidedData = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'email_address' => $mockEmail,
            'password' => $mockPassword,
            'password_confirm' => $mockPassword,

        ];
        $mockHash = 'gibberish';
        $mockInsertData = [
            'email' => $mockEmail,
            'password_hash' => $mockHash,
            'first_name' => 'firstname',
            'last_name' => 'lastname',
        ];

        $mockPasswordObject = m::mock(Password::class);
        $mockPasswordObject->shouldReceive('createHashedPassword')
            ->with($mockPassword)
            ->andReturn($mockPasswordObject);
        $mockPasswordObject->shouldReceive('getHash')
            ->andReturn($mockHash);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('doAuthorsExist')
            ->andReturn(false);
        $mockDataObject->shouldReceive('createNewAuthor')
            ->with($mockInsertData)
            ->andReturn($mockId);

        $object = new AuthorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $returned = $object->create($mockProvidedData);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test login() method returns false if invalid password
     */
    public function testLoginReturnsFalseIfNotValidPassword()
    {
        $mockEmail = 'sample@email.com';
        $mockPassword = 'somepassword';
        $mockProvidedData = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'email_address' => $mockEmail,
            'password' => $mockPassword,
            'password_confirm' => $mockPassword,

        ];
        $mockHash = 'gibberish';
        $mockUserRecord = [
            'email' => $mockEmail,
            'password_hash' => $mockHash,
            'first_name' => 'firstname',
            'last_name' => 'lastname',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchAuthorDataByEmail')
            ->with($mockEmail)
            ->andReturn($mockUserRecord);

        $mockPasswordObject = m::mock(Password::class);
        $mockPasswordObject->shouldReceive('verifyPassword')
            ->with($mockPassword, $mockHash)
            ->andReturn(false);
        $mockPasswordObject->shouldReceive('getHash')
            ->andReturn($mockHash);

        $object = new AuthorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $returned = $object->login($mockProvidedData);

        $this->assertFalse($returned);
    }

    /**
     * Test login() method updates password if necessary and sets login session
     */
    public function testLoginUpdatesPasswordIfNecessaryAndSetsSession()
    {
        $mockAuthorId = 1;
        $mockEmail = 'sample@email.com';
        $mockPassword = 'somepassword';
        $mockProvidedData = [
            'email_address' => $mockEmail,
            'password' => $mockPassword,
        ];
        $mockSessionArgs = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'email_address' => $mockEmail,
            'author_id' => $mockAuthorId,

        ];
        $mockHash = 'gibberish';
        $mockHash2 = 'gibberishextra';
        $mockUserRecord = [
            'author_id' => $mockAuthorId,
            'email' => $mockEmail,
            'password_hash' => $mockHash,
            'first_name' => 'firstname',
            'last_name' => 'lastname',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('set')
            ->with('author', $mockSessionArgs);

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchAuthorDataByEmail')
            ->with($mockEmail)
            ->andReturn($mockUserRecord);
        $mockDataObject->shouldReceive('updatePassword')
            ->with($mockAuthorId, $mockHash2);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $mockPasswordObject = m::mock(Password::class);
        $mockPasswordObject->shouldReceive('verifyPassword')
            ->with($mockPassword, $mockHash)
            ->andReturn(true);
        $mockPasswordObject->shouldReceive('getHash')
            ->andReturn($mockHash2);
        $mockPasswordObject->shouldReceive('isSecurePassword')
            ->andReturn(false);

        $object = new AuthorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $returned = $object->login($mockProvidedData);

        $this->assertTrue($returned);
    }

    /**
     * Test logout() method removes session and returns true
     */
    public function testLogoutRemovesSessionAndReturnsTrue()
    {
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('remove')
            ->with('author')
            ->andReturn(true);

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new AuthorApi($mockDataObject);
        $returned = $object->logout();

        $this->assertTrue($returned);
    }

    /**
     * Test logout() method returns false if session removal fails
     */
    public function testLogoutReturnsFalseIfSessionRemovalFailure()
    {
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('remove')
            ->with('author')
            ->andReturn(false);

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new AuthorApi($mockDataObject);
        $returned = $object->logout();

        $this->assertFalse($returned);
    }

    /**
     * Test fetchBasics() returns data if provided id
     */
    public function testFetchBasicsReturnsDataIfProvidedId()
    {
        $mockId = 1;
        $mockData = [
            'key' => 'somevalue',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchAuthorBasicDataById')
            ->with($mockId)
            ->andReturn($mockData);

        $object = new AuthorApi($mockDataObject);
        $returned = $object->fetchBasics($mockId);

        $this->assertSame($mockData, $returned);
    }

    /**
     * Test fetchFull() returns data if provided id
     */
    public function testFetchFullReturnsDataIfProvidedId()
    {
        $mockId = 1;
        $mockData = [
            'key' => 'somevalue',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(AuthorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchAuthorDataById')
            ->with($mockId)
            ->andReturn($mockData);

        $object = new AuthorApi($mockDataObject);
        $returned = $object->fetchFull($mockId);

        $this->assertSame($mockData, $returned);
    }
}
