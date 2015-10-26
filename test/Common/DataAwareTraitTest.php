<?php

namespace BasicBlog\Common;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class DataAwareTraitTest
 *
 * @package BasicBlog\Common
 */
class DataAwareTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testDataObjectIsSetFromConstructor()
    {
        $mockApp = m::mock(\Silex\Application::class);
        $mockAppAwareDataObject = m::mock(\stdClass::class);
        $mockAppAwareDataObject->shouldReceive('__construct')
            ->with($mockApp);

        $trait = $this->getObjectForTrait(DataAwareTrait::class);
        $trait->__construct($mockAppAwareDataObject);
        //todo: avoiding this setConstructorArgs behavior by creating a factory
        // plus trait accessors for the Page class to request?
        $this->assertSame(
            $mockAppAwareDataObject,
            $trait->getDataObject()
        );
    }

    /**
     * Test accessors
     */
    public function testAccessors()
    {
        $mockApp = m::mock(\Silex\Application::class);
        $mockAppAwareDataObject = m::mock(\stdClass::class);
        $mockAppAwareDataObject->shouldReceive('__construct')
            ->with($mockApp);

        $trait = $this->getObjectForTrait(DataAwareTrait::class);
        $trait->__construct($mockAppAwareDataObject);

        $mockAppAwareDataObject2 = m::mock(\stdClass::class); //, [$mockApp]);
        $mockAppAwareDataObject2->shouldReceive('__construct')
            ->with($mockApp);

        $trait->setDataObject($mockAppAwareDataObject2);
        $this->assertSame(
            $mockAppAwareDataObject2,
            $trait->getDataObject()
        );
    }
}
