<?php

namespace BasicBlog\Common;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class ApplicationAwareTraitTest
 *
 * @package BasicBlog\Common
 */
class ApplicationAwareTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testAppIsSetFromConstructor()
    {
        $mockApp = m::mock(\Silex\Application::class);

        $trait = $this->getObjectForTrait(ApplicationAwareTrait::class);
        $trait->__construct($mockApp);
        //todo: avoiding this setConstructorArgs behavior by creating a factory
        // plus trait accessors for the Page class to request?
        $this->assertSame(
            $mockApp,
            $trait->getApp()
        );
    }

    /**
     * Test accessors
     */
    public function testAccessors()
    {
        $mockApp = m::mock(\Silex\Application::class);

        $trait = $this->getObjectForTrait(ApplicationAwareTrait::class);
        $trait->__construct($mockApp);

        $mockApp2 = m::mock(\Silex\Application::class);

        $trait->setApp($mockApp2);
        $this->assertSame(
            $mockApp2,
            $trait->getApp()
        );
    }
}
