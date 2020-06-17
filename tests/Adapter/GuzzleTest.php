<?php

namespace Incapsula\API\Tests\Adapter;

class GuzzleTest extends \TestCase
{
    private $client;

    public function setUp(): void
    {
        $auth = $this->createMock(\Incapsula\API\Parameters\Auth::class, ['getRequestParameters']);
        $auth->method('getRequestParameters')
            ->willReturn(['X-Testing' => 'Test']);
        
        $this->adapter = new \Incapsula\API\Adapter\Guzzle($auth);
    }

    public function testRequest()
    {
        $response = $this->adapter->request('https://httpbin.org/post');

        $this->assertEquals('application/json', $response->headers->{"Content-Type"});
        $this->assertEquals('Test', $response->json->{'X-Testing'});

        $response = $this->adapter->request('https://httpbin.org/post', ['X-Another-Test' => 'Test2']);

        $this->assertEquals('Test2', $response->json->{'X-Another-Test'});
    }

    public function testIncapsulaException()
    {
        $class = new \ReflectionClass(\Incapsula\API\Adapter\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('Adapter/errorResponse.json');

        $this->expectException(\Incapsula\API\Adapter\IncapsulaException::class);
        $method->invokeArgs($this->adapter, [$response]);
        $response = $this->adapter->getDebugInfo();
    }

    public function testJSONException()
    {
        $class = new \ReflectionClass(\Incapsula\API\Adapter\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('Adapter/notJson');

        $this->expectException(\Incapsula\API\Adapter\JSONException::class);
        $method->invokeArgs($this->adapter, [$response]);
    }

    public function testGetDebugInfo()
    {
        $class = new \ReflectionClass(\Incapsula\API\Adapter\Guzzle::class);
        $property = $class->getProperty('debug_info');
        $property->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('Adapter/errorResponse.json');
        $property->setValue($this->adapter, json_decode($response->getBody()));

        $result = $this->adapter->getDebugInfo();
        
        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('debug_info', $result);
        $this->assertEquals('Site has no valid DNS records', $result->debug_info->problem);
    }
}
