<?php

namespace Incapsula\API\Tests;

class GuzzleTest extends \TestCase
{
    private $client;

    public function setUp(): void
    {
        $auth = $this->createMock(\Incapsula\API\ApiKey::class, ['toArray']);
        $auth->method('toArray')
            ->willReturn(['X-Testing' => 'Test']);
        
        $this->adapter = new \Incapsula\API\Guzzle($auth);
    }

    public function testRequest()
    {
        $client = $this->createMock(\GuzzleHttp\Client::class, ['request']);
        $client->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('/test/uri'),
                $this->equalTo([
                    'json' => [
                        'test' => 'option',
                        'X-Testing' => 'Test'
                    ]
                ])
            )
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], '{}'));
        
        $this->adapter->setClient($client);
        $response = $this->adapter->request('/test/uri', ['test' => 'option']);
    }

    public function testCheckError()
    {
        $class = new \ReflectionClass(\Incapsula\API\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $property_res = $class->getProperty('res');
        $property_res->setAccessible(true);
        $property_res->setValue($this->adapter, 1);

        $this->expectException(\Incapsula\API\IncapsulaException::class);
        $method->invoke($this->adapter);
    }

    public function testParseResponse()
    {
        $class = new \ReflectionClass(\Incapsula\API\Guzzle::class);
        $method = $class->getMethod('parseResponse');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('notJson');

        $this->expectException(\Incapsula\API\JSONException::class);
        $method->invokeArgs($this->adapter, [$response]);
    }

    public function testGetDebugInfo()
    {
        $class = new \ReflectionClass(\Incapsula\API\Guzzle::class);
        $property = $class->getProperty('debug_info');
        $property->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('errorResponse.json');
        $property->setValue($this->adapter, json_decode($response->getBody()));

        $result = $this->adapter->getDebugInfo();
        
        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('debug_info', $result);
        $this->assertEquals('Site has no valid DNS records', $result->debug_info->problem);
    }
}
