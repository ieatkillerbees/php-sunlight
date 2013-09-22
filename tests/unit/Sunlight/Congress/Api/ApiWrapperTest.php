<?php
/**
 * Created by JetBrains PhpStorm.
 * User: squinones
 * Date: 9/22/13
 * Time: 12:50 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Sunlight\Congress\Tests;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Sunlight\Congress\Api\ApiWrapper;
use Sunlight\Congress\Containers\FieldList;
use Sunlight\Congress\Containers\Filter;
use Sunlight\Congress\Containers\Sort;

class ApiWrapperTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructionWithApiKeyInEnvVar()
    {
        putenv("SUNLIGHT_API_KEY=foobar");
        $wrapper = new ApiWrapper();
        $this->assertEquals(getenv("SUNLIGHT_API_KEY"), $wrapper->getApiKey());
        putenv("SUNLIGHT_API_KEY");
    }

    public function testConstructionWithApiKeyAsParam()
    {
        $wrapper = new ApiWrapper("foobar");
        $this->assertEquals("foobar", $wrapper->getApiKey());
    }

    public function testContructionWithNoApiKeySet()
    {
        $wrapper = new ApiWrapper();
        $this->setExpectedException("RuntimeException", "You must set a valid API key");
        $wrapper->getApiKey();
    }

    public function testGetUrlThrowsExceptionWhenUrlNotSet()
    {
        $wrapper = new ApiWrapper();
        $this->setExpectedException("RuntimeException", "No URL configured for this wrapper!");
        $wrapper->getUrl();
    }

    public function testGetUrl()
    {
        $wrapper = new ApiWrapper();
        $wrapper->setUrl("http://foo");
        $this->assertEquals("http://foo", $wrapper->getUrl());
    }

    public function testGetClientReturnsGuzzleClient()
    {
        $wrapper = new ApiWrapper();
        $wrapper->setUrl("http://foo");
        $this->assertInstanceOf("Guzzle\\Http\\Client", $wrapper->getClient());
    }

    public function testFindReturnsValidResponseObject()
    {
        $wrapper = new ApiWrapper("abc123");
        $wrapper->setUrl("http://foo");

        $respJson = json_encode(array(
                                     'results' => [],
                                     'count'   => 0
                                ));

        $request = \Mockery::mock('Guzzle\Http\Message\Request[send]', array('get', 'http://foo'));
        $request->shouldDeferMissing();
        $request->shouldReceive('send')->withNoArgs()->andReturn(new Response(200, null, $respJson));

        $client = \Mockery::mock('Guzzle\Http\Client[get]', array('http://foo'));
        $client->shouldDeferMissing();
        $client->shouldReceive('get')->withNoArgs()->andReturn($request);

        $wrapper->setClient($client);
        $this->assertInstanceOf('Sunlight\Congress\Api\ApiResponse', $wrapper->find());

    }

    public function testSettingFieldsFromArray()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo", "bar", "baz");
        $wrapper->fields($fields);
        $this->assertEquals(implode(",", $fields), $wrapper->getFields());
    }

    public function testSettingFieldsFromFieldList()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo", "bar", "baz");
        $wrapper->fields(new FieldList($fields));
        $this->assertEquals(implode(",", $fields), $wrapper->getFields());
    }

    public function testSettingSortFromArray()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo" => "asc", "bar" => "desc");
        $wrapper->sort($fields);
        $this->assertEquals("foo__asc,bar__desc", $wrapper->getSort());
    }

    public function testSettingSortFromSort()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo" => "asc", "bar" => "desc");
        $wrapper->sort(new Sort($fields));
        $this->assertEquals("foo__asc,bar__desc", $wrapper->getSort());
    }

    public function testSettingFilterFromArray()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo" => "bar", "baz" => "bam");
        $wrapper->filter($fields);
        $this->assertEquals(new Filter($fields), $wrapper->getFilter());
    }

    public function testSettingFilterFromFilter()
    {
        $wrapper = new ApiWrapper();
        $fields  = array("foo" => "bar", "baz" => "bam");
        $wrapper->filter(new Filter($fields));
        $this->assertEquals(new Filter($fields), $wrapper->getFilter());
    }

    /**
     * @param $fields
     * @dataProvider invalidFieldsProvider
     */
    public function testSettingFieldsFromInvalidInput($fields)
    {
        $wrapper = new ApiWrapper();
        $this->setExpectedException("BadMethodCallException", 'Sunlight\Congress\Api\ApiWrapper::fields - first argument must be array or instance of Sunlight\Congress\Api\FieldSet');
        $wrapper->fields($fields);
    }

    /**
     * @param $fields
     * @dataProvider invalidFieldsProvider
     */
    public function testSettingSortFromInvalidInput($fields)
    {
        $wrapper = new ApiWrapper();
        $this->setExpectedException("BadMethodCallException", 'Sunlight\Congress\Api\ApiWrapper::sort - first argument must be array or instance of Sunlight\Congress\Api\Sort');
        $wrapper->sort($fields);
    }

    /**
     * @param $fields
     * @dataProvider invalidFieldsProvider
     */
    public function testSettingFilterFromInvalidInput($fields)
    {
        $wrapper = new ApiWrapper();
        $this->setExpectedException("BadMethodCallException", 'Sunlight\Congress\Api\ApiWrapper::filter - first argument must be array or instance of Sunlight\Congress\Api\Filter');
        $wrapper->filter($fields);
    }

    public function invalidFieldsProvider()
    {
        return array(
            array(3.14159),                     // float
            array(42),                          // int
            array(new \stdClass()),             // object
            array("foobar"),                    // string literal
            array("true"),                      // boolean
        );
    }

}
