<?php

namespace Squinones\Sunlight\Congress\Tests;


use Guzzle\Http\Message\Response;
use Squinones\Sunlight\Congress\Api\ApiResponse;

class ApiResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRequestReturnsRequestInterface()
    {
        $respJson = json_encode(array(
            'results' => array(),
            'count'   => 0
        ));

        $request = \Mockery::mock('Guzzle\Http\Message\Request[send]', array('get', 'http://foo'));
        $request->shouldDeferMissing();
        $request->shouldReceive('send')->withNoArgs()->andReturn(new Response(200, null, $respJson));

        $response = new ApiResponse($request);

        $this->assertInstanceOf(
            'Guzzle\Http\Message\RequestInterface',
            $response->getRequest(),
            "ApiResponse::getRequest() did not return an instance of RequestInterface"
        );

        $this->assertTrue(is_array($response->getResponse()));

        $this->assertInternalType("integer", $response->count());
    }

    public function testCurrentReturnsValue()
    {
        $respJson = json_encode(array(
            'results' => array('foo', 'bar'),
            'count'   => 2
        ));

        $request = \Mockery::mock('Guzzle\Http\Message\Request[send]', array('get', 'http://foo'));
        $request->shouldDeferMissing();
        $request->shouldReceive('send')->withNoArgs()->andReturn(new Response(200, null, $respJson));

        $response = new ApiResponse($request);

        $this->assertNotNull($response->current());
        $this->assertEquals('foo', $response->current());
        $response->next();
        $this->assertEquals('bar', $response->current());
        $response->next();
        $this->assertNull($response->current());
    }

    public function testCurrentReturnsNull()
    {
        $respJson = json_encode(array(
            'results' => array(),
            'count'   => 0
        ));

        $request = \Mockery::mock('Guzzle\Http\Message\Request[send]', array('get', 'http://foo'));
        $request->shouldDeferMissing();
        $request->shouldReceive('send')->withNoArgs()->andReturn(new Response(200, null, $respJson));

        $response = new ApiResponse($request);

        $this->assertNull($response->current());
    }

}
