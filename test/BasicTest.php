<?php

namespace BasicBlog;

use Silex\WebTestCase;

/**
 * Basic test to confirm route functionality
 */
class BasicTest extends WebTestCase
{
    /**
     * Boilerplate for Silex application aware functional testing
     *
     * @return Silex\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../src/app.php';
    }

    /**
     * Test home page request returns expected value and OK status
     */
    public function testStatusRoute()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(
            'Successful response',
            $client->getResponse()->getContent()
        );
    }
}