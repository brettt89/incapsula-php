<?php

namespace IncapsulaAPI\Test\Adapter;

class GuzzleTest extends \TestCase
{
    private $client;

    public function setUp(): void
    {
        $auth = $this->createMock(\IncapsulaAPI\Auth\ApiKey::class, ['toArray']);
        $auth->method('toArray')
            ->willReturn(['X-Testing' => 'Test']);
        
        $this->adapter = new \IncapsulaAPI\Adapter\Guzzle($auth);
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
                    'form_params' => [
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
        $class = new \ReflectionClass(\IncapsulaAPI\Adapter\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $property_res = $class->getProperty('res');
        $property_res->setAccessible(true);
        $property_res->setValue($this->adapter, 1);

        $property_res_message = $class->getProperty('res_message');
        $property_res_message->setAccessible(true);
        $property_res_message->setValue($this->adapter, 'Test Message');

        $property_debug_info = $class->getProperty('debug_info');
        $property_debug_info->setAccessible(true);
        $property_debug_info->setValue($this->adapter, ['problem' => 'Test Problem']);

        $this->expectException(\IncapsulaAPI\Adapter\IncapsulaException::class);
        $method->invoke($this->adapter);
    }

    public function testParseResponseJSONException()
    {
        $class = new \ReflectionClass(\IncapsulaAPI\Adapter\Guzzle::class);
        $method = $class->getMethod('parseResponse');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('notJson');

        $this->expectException(\IncapsulaAPI\Adapter\JSONException::class);
        $method->invokeArgs($this->adapter, [$response]);
    }

    public function testParseResponse()
    {
        $class = new \ReflectionClass(\IncapsulaAPI\Adapter\Guzzle::class);
        $method = $class->getMethod('parseResponse');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('Site/listSites.json');
        $result = $method->invokeArgs($this->adapter, [$response]);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('sites', $result);
        $this->assertIsArray($result->sites);
    }

    public function testGetDebugInfo()
    {
        $class = new \ReflectionClass(\IncapsulaAPI\Adapter\Guzzle::class);
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
