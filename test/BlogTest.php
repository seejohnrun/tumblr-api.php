<?php

class BlogTest extends PHPUnit_Framework_TestCase {

    public function providerCalls() {
        return array(

            // getFollowers
            array(function () { Tumblr\API::getFollowers(10, 10); }, 'GET', '/followers', array('limit' => 10, 'offset' => 10)),
            array(function () { Tumblr\API::getFollowers(10); }, 'GET', '/followers', array('limit' => 10)),
            array(function () { Tumblr\API::getFollowers(null, 5); }, 'GET', '/followers', array('offset' => 5)),
            array(function () { Tumblr\API::getFollowers(); }, 'GET', '/followers', array()),

            // getQueuedPosts
            array(function () { Tumblr\API::getQueuedPosts(); }, 'GET', '/posts/queue', array()),

            // getDraftPosts
            array(function () { Tumblr\API::getDraftPosts(); }, 'GET', '/posts/draft', array()),

            // getSubmissionPosts
            array(function () { Tumblr\API::getSubmissionPosts(); }, 'GET', '/posts/submission', array()),

            // getPostByID
            array(function () { Tumblr\API::getPostByID(123); }, 'GET', '/posts', array('id' => 123, 'api_key' => API_KEY)),

        );
    }

    /**
     * @dataProvider providerCalls
     */
    public function testCalls($callable, $type, $path, $params) {
        // Build a mock
        $instance = $this->getMock('stdClass', array('request'));

        // Response build
        $response = null;
        $response->posts = array();

        // And then set it to check for the proper response (one time)
        $instance->expects($this->once())
            ->method('request')
            ->with($this->equalTo($type), $this->equalTo($path), $this->equalTo($params))
            ->will($this->returnValue($response));

        // Set the instance to have an api_key
        $instance->api_key = API_KEY;

        // Set this mock as the Singleton
        $ref = new ReflectionClass('Singleton');
        $prop = $ref->getProperty('singleton_instance');
        $prop->setAccessible(true);
        $prop->setValue($instance);

        // And then run the callback to check the results
        $callable();
    }

}
