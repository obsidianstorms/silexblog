<?php

namespace BasicBlog;

//use Silex\WebTestCase;
//use Symfony\Component\BrowserKit\Client;
use PHPUnit_Framework_TestCase;
use Mockery as m;


class ExistTest extends PHPUnit_Framework_TestCase
{
//    /**
//     * test / (home)
//     *
//     * @return void
//     */
//    public function testHome()
//    {
//        $client = $this->createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertTrue($client->getResponse()->isOk());
//    }
    /**
     * basic test
     */
    public function testWorking()
    {
        $this->assertTrue(true);
    }

}
