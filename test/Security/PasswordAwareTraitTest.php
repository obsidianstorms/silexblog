<?php

namespace BasicBlog\Security;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class PasswordAwareTraitTest
 *
 * @package BasicBlog\Security
 */
class PasswordAwareTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test accessors
     */
    public function testAccessors()
    {
        $trait = $this->getObjectForTrait(PasswordAwareTrait::class);

        $mockPasswordObject = m::mock(Password::class);

        $this->assertNull(
            $trait->getPasswordObject()
        );

        $this->assertSame(
            $trait,
            $trait->setPasswordObject($mockPasswordObject)
        );

        $this->assertSame(
            $mockPasswordObject,
            $trait->getPasswordObject()
        );
    }
}
