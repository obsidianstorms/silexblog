<?php

namespace BasicBlog\Commentator;

use BasicBlog\Security\Password;
use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class CommentatorApiTest
 *
 * Test the commentator api object
 *
 * @package BasicBlog\Commentator
 */
class CommentatorApiTest extends PHPUnit_Framework_TestCase
{

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

        $mockInsertData = [
            'username' => 'somevalue',
            'password' => 'somepassword',
            'password_confirm' => 'somedifferentpassword',

        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);

        $object = new CommentatorApi($mockDataObject);
        $object->create($mockInsertData);
    }

    /**
     * Test create() returns false if username already exists
     */
    public function testCreateReturnsFalseIfUsernameExists()
    {
        $mockUsername = 'somevalue';
        $mockInsertData = [
            'username' => $mockUsername,
            'password' => 'somepassword',
            'password_confirm' => 'somepassword',

        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('doesUsernameExist')
            ->with($mockUsername)
            ->andReturn(true);

        $object = new CommentatorApi($mockDataObject);
        $object->create($mockInsertData);
    }

    /**
     * Test create() returns true if query succeeds
     */
    public function testCreateReturnsIdIfSuccessful()
    {
        $mockId = 1;
        $mockUsername = 'somevalue';
        $mockPassword = 'somepassword';
        $mockHash = 'gibberish';
        $mockProvidedData = [
            'username' => $mockUsername,
            'password' => $mockPassword,
            'password_confirm' => $mockPassword,
        ];
        $mockInsertData = [
            'username' => $mockUsername,
            'password_hash' => $mockHash,
        ];

        $mockPasswordObject = m::mock(Password::class);
        $mockPasswordObject->shouldReceive('createHashedPassword')
            ->with($mockPassword)
            ->andReturn($mockPasswordObject);
        $mockPasswordObject->shouldReceive('getHash')
            ->andReturn($mockHash);

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('doesUsernameExist')
            ->with($mockUsername)
            ->andReturn(false);
        $mockDataObject->shouldReceive('create')
        ->with($mockInsertData)
        ->andReturn($mockId);

        $object = new CommentatorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $returned = $object->create($mockProvidedData);

        $this->assertSame($mockId, $returned);
    }

    /**
     * Test login() method returns false if invalid password
     */
    public function testLoginReturnsFalseIfNotValidPassword()
    {
        $mockUsername = 'somevalue';
        $mockPassword = 'somepassword';
        $mockHash = 'gibberish';
        $mockProvidedData = [
            'username' => $mockUsername,
            'password' => $mockPassword,
        ];
        $mockUserRecord = [
            'password_hash' => $mockHash,
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchCommentatorByUsername')
            ->with($mockUsername)
            ->andReturn($mockUserRecord);

        $mockPasswordObject = m::mock(Password::class);
        $mockPasswordObject->shouldReceive('verifyPassword')
            ->with($mockPassword, $mockHash)
            ->andReturn(false);
        $mockPasswordObject->shouldReceive('getHash')
            ->andReturn($mockHash);

        $object = new CommentatorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $returned = $object->login($mockProvidedData);

        $this->assertFalse($returned);
    }

    /**
     * Test login() method updates password if hash quality has been updated
     */
    public function testLoginUpdatesPasswordIfNecessaryAndSetsSession()
    {
        $mockCommentatorId = 1;
        $mockUsername = 'somevalue';
        $mockPassword = 'somepassword';
        $mockHash = 'gibberish';
        $mockHash2 = 'gibberishupdate';
        $mockProvidedData = [
            'username' => $mockUsername,
            'password' => $mockPassword,
        ];
        $mockUserRecord = [
            'username' => $mockUsername,
            'password_hash' => $mockHash,
            'commentator_id' => $mockCommentatorId,
        ];

        $mockSessionArgs = [
            'username' => $mockUsername,
            'commentator_id' => $mockCommentatorId,
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('set')
            ->with('commentator', $mockSessionArgs);

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchCommentatorByUsername')
            ->with($mockUsername)
            ->andReturn($mockUserRecord);
        $mockDataObject->shouldReceive('updatePassword')
            ->with($mockCommentatorId, $mockHash2);
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

        $object = new CommentatorApi($mockDataObject);
        $object->setPasswordObject($mockPasswordObject);
        $object->login($mockProvidedData);
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
            ->with('commentator')
            ->andReturn(true);

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new CommentatorApi($mockDataObject);
        $returned = $object->logout();

        $this->assertTrue($returned);
    }

    /**
     * Test logout() returns false if session removal fails
     */
    public function testLogoutReturnsFalseIfSessionRemovalFailure()
    {
        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockSessionObject = m::mock(\stdClass::class);
        $mockSessionObject->shouldReceive('remove')
            ->with('commentator')
            ->andReturn(false);

        $mockDataObject = m::mock(CommentatorData::class, [$mockApp]);
        $mockDataObject->shouldReceive('getSession')
            ->andReturn($mockSessionObject);

        $object = new CommentatorApi($mockDataObject);
        $returned = $object->logout();

        $this->assertFalse($returned);
    }

    /**
     * Test fetchBasics() method returns data if provided id
     */
    public function testFetchBasicsReturnsDataIfProvidedId()
    {
        $mockId = 1;
        $mockData = [
            'key' => 'somevalue',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorApi::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchCommentatorBasicDataById')
            ->with($mockId)
            ->andReturn($mockData);

        $object = new CommentatorApi($mockDataObject);
        $returned = $object->fetchBasics($mockId);

        $this->assertSame($mockData, $returned);
    }

    /**
     * Test fetchFull() method returns data if provided id
     */
    public function testFetchFullReturnsDataIfProvidedId()
    {
        $mockId = 1;
        $mockData = [
            'key' => 'somevalue',
        ];

        $mockApp = m::mock(\Silex\Application::class)
            ->makePartial();

        $mockDataObject = m::mock(CommentatorApi::class, [$mockApp]);
        $mockDataObject->shouldReceive('fetchCommentatorFullDataById')
            ->with($mockId)
            ->andReturn($mockData);

        $object = new CommentatorApi($mockDataObject);
        $returned = $object->fetchFull($mockId);

        $this->assertSame($mockData, $returned);
    }
}
