<?php

namespace BasicBlog\Author;

use BasicBlog\Security\Password;
use PHPUnit_Framework_TestCase;

/**
 * Class AuthorTest
 *
 * Test the representation of a complete author object
 *
 * @package BasicBlog\Author
 */
class AuthorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $object;

    /**
     * Setup the object to be tested
     */
    protected function setUp()
    {
        $this->object = new Author();
    }

    /**
     * Test getter and setters for author id
     */
    public function testAuthorIdGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getAuthorId(),
            'Author id property was incorrectly populated at instantiation.'
        );

        $expectedValue = 1;
        $this->assertSame(
            $this->object,
            $this->object->setAuthorId($expectedValue),
            'Author id setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getAuthorId(),
            'Author id property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for author id
     */
    public function testAuthorIdSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            Author::MESSAGE_INVALID_INTEGER,
            0
        );

        $this->object->setAuthorId('invalid value');
    }

    /**
     * Test getter and setters for email
     */
    public function testEmailGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getEmail(),
            'Email property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'sample@sample.com';
        $this->assertSame(
            $this->object,
            $this->object->setEmail($expectedValue),
            'Email setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getEmail(),
            'Email property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for author id
     */
    public function testEmailSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            Author::MESSAGE_INVALID_STRING,
            1
        );

        $this->object->setEmail([]);
    }

    /**
     * Test getter and setters for password
     */
    public function testPasswordGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getPassword(),
            'Password property was incorrectly populated at instantiation.'
        );

        $expectedValue = new Password();
        $this->assertSame(
            $this->object,
            $this->object->setPassword($expectedValue),
            'Password setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getPassword(),
            'Password property did not return expected value.'
        );
    }

    /**
     * Test getter and setters for firstName
     */
    public function testFirstNameGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getFirstName(),
            'FirstName property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'first name';
        $this->assertSame(
            $this->object,
            $this->object->setFirstName($expectedValue),
            'FirstName setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getFirstName(),
            'FirstName property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for author id
     */
    public function testFirstNameSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            Author::MESSAGE_INVALID_STRING,
            2
        );

        $this->object->setFirstName([]);
    }

    /**
     * Test getter and setters for lastName
     */
    public function testLastNameGetterAndSetter()
    {
        $this->assertNull(
            $this->object->getLastName(),
            'LastName property was incorrectly populated at instantiation.'
        );

        $expectedValue = 'last name';
        $this->assertSame(
            $this->object,
            $this->object->setLastName($expectedValue),
            'LastName setter did not return static object.'
        );

        $this->assertSame(
            $expectedValue,
            $this->object->getLastName(),
            'LastName property did not return expected value.'
        );
    }

    /**
     * Test exception for invalid setter value for lastNane
     */
    public function testLastNamelSetterThrowsInvalidArgumentExceptionIfInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            Author::MESSAGE_INVALID_STRING,
            3
        );

        $this->object->setLastName([]);
    }

}