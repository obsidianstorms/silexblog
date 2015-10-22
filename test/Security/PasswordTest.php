<?php

namespace BasicBlog\Security;

use PHPUnit_Framework_TestCase;

/**
 * Class PasswordTest
 *
 * @package BasicBlog\Security
 */
class PasswordTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test createHashedPassword() returns not plain text value
     */
    public function testHashedPasswordCreateAndGetter()
    {
        $plainValue = 'some plain text';
        $object = new Password();

        $this->assertNull(
            $object->getHash(),
            'Hash property was incorrectly populated at instantiation.'
        );

        $this->assertSame(
            $object,
            $object->createHashedPassword($plainValue),
            'Hash creation did not return static object.'
        );

        $this->assertNotNull(
            $object->getHash(),
            'Hash value was not generated.'
        );

        $this->assertNotEquals(
            $plainValue,
            $object->getHash(),
            'Hash value unexpectedly matches plain text value.'
        );
    }

    /**
     * Test createHashedPassword() throws RuntimeExeption
     */
    public function testCreateHashedPasswordThrowsRuntimeExceptionOnFailure()
    {
        $this->markTestIncomplete('This test would require unknown causes to create a method failure.');
    }

    /**
     * Test verifyPassword() returns false if not match
     */
    public function testVerifyPasswordReturnsFalseIfNotMatch()
    {
        $attemptedPassword = 'some plain text';
        $object = new Password();
        $object->createHashedPassword('original password');
        $hash = $object->getHash();
        $result = $object->verifyPassword($attemptedPassword, $hash);
        $this->assertFalse($result);
    }

    /**
     * Test verifyPassword() returns true if match
     */
    public function testVerifyPasswordReturnsTrueIfMatch()
    {
        $attemptedPassword = 'some plain text';
        $object = new Password();
        $object->createHashedPassword($attemptedPassword);
        $hash = $object->getHash();
        $result = $object->verifyPassword($attemptedPassword, $hash);
        $this->assertTrue($result);
    }

    /**
     * Test isSecurePassword() returns true by default
     */
    public function testIsSecurePasswordReturnsTrueByDefault()
    {
        $object = new Password();
        $this->assertTrue($object->isSecurePassword());
    }
}