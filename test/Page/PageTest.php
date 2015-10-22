<?php
//
//namespace BasicBlog\Page;
//
//use Silex\WebTestCase;
//
///**
// * Basic test to confirm route functionality
// */
//class PageTest extends WebTestCase
//{
//    /**
//     * Boilerplate for Silex application aware functional testing
//     *
//     * @return Silex\Application
//     */
//    public function createApplication()
//    {
//        return require __DIR__ . '/../../src/app.php';
//    }
//
//    /**
//     * Test home page request returns expected value and OK status
//     */
//    public function testIndex()
//    {
//        $client = $this->createClient();
//        $client->request('GET', '/');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: index',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test login page request returns expected value and OK status
//     */
//    public function testLogin()
//    {
//        $client = $this->createClient();
//        $client->request('POST', '/login');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: login',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test newPost page request returns expected value and OK status
//     *
//     * @return int
//     */
//    public function testNewPost()
//    {
//        $client = $this->createClient();
//        $client->request('POST', '/post');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: newPost',
//            $client->getResponse()->getContent()
//        );
//
//        return 1; //id value
//    }
//
//    /**
//     * Test viewPost page request returns expected value and OK status
//     *
//     * @depends testNewPost
//     */
//    public function testViewPost($id)
//    {
//        $client = $this->createClient();
//        $client->request('GET', '/' . $id);
//
//        $this->assertTrue($client->getResponse()->isOk());
//    }
//
//    /**
//     * Test editPost page request returns expected value and OK status
//     *
//     * @depends testNewPost
//     */
//    public function testEditPost($id)
//    {
//        $client = $this->createClient();
//        $client->request('GET', '/' . $id . '/edit');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: editPost',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test changePost page request returns expected value and OK status
//     *
//     * @depends testNewPost
//     */
//    public function testChangePost($id)
//    {
//        $client = $this->createClient();
//        $client->request('PUT', '/' . $id);
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: changePost',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test removePost page request returns expected value and OK status
//     *
//     * @depends testNewPost
//     */
//    public function testRemovePost($id)
//    {
//        $client = $this->createClient();
//        $client->request('DELETE', '/' . $id);
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: removePost',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test newComment page request returns expected value and OK status
//     *
//     * @depends testNewPost
//     *
//     * @return int[]
//     */
//    public function testNewComment($id)
//    {
//        $client = $this->createClient();
//        $client->request('POST', '/' . $id . '/comment');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: newComment',
//            $client->getResponse()->getContent()
//        );
//
//        return [1, 1]; // post_id, comment_id
//    }
//
//    /**
//     * Test viewComment page request returns expected value and OK status
//     *
//     * @depends testNewComment
//     */
//    public function testViewComment($ids)
//    {
//        $client = $this->createClient();
//        $client->request('GET', '/' . $ids[0] . '/' . $ids[1]);
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: viewComment',
//            $client->getResponse()->getContent()
//        );
//    }
//
//    /**
//     * Test removeComment page request returns expected value and OK status
//     *
//     * @depends testNewComment
//     */
//    public function testRemoveComment($ids)
//    {
//        $client = $this->createClient();
//        $client->request('DELETE', '/' . $ids[0] . '/' . $ids[1]);
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertEquals(
//            'Successful response: removeComment',
//            $client->getResponse()->getContent()
//        );
//    }
//}