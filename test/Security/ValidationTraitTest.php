<?php

namespace BasicBlog\Security;

use PHPUnit_Framework_TestCase;

/**
 * Class ValidationTraitTest
 *
 * @package BasicBlog\Security
 */
class ValidationTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that the checkDataIntegrity() method throws
     * \InvalidArgumentException if provided data fails the filter.
     */
    public function testCheckDataIntegrityThrowsInvalidArgumentException()
    {
        $mockData = [
            'integer' => 'noninteger',
        ];

        $mockFieldFilter = [
            'integer' => FILTER_VALIDATE_INT,
        ];

        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid data submitted: integer.',
            1
        );

        $object = $this->getObjectForTrait('BasicBlog\Security\ValidationTrait');
        $object->checkDataIntegrity($mockData, $mockFieldFilter);
    }

    /**
     * Test that the checkDataIntegrity() method returns expected array if data
     * passes the filter.
     */
    public function testCheckDataIntegrityReturnsArray()
    {
        $mockData = [
            'string' => 'noninteger',
        ];

        $mockFieldFilter = [
            'string' => FILTER_SANITIZE_STRING,
        ];

        $expectedData = [
            'string' => 'noninteger',
        ];

        $object = $this->getObjectForTrait('BasicBlog\Security\ValidationTrait');
        $returned = $object->checkDataIntegrity($mockData, $mockFieldFilter);

        $this->assertSame($expectedData, $returned);
    }
}
