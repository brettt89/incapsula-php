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

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Test', $body->form->{'X-Testing'});

        $response = $this->adapter->request('https://httpbin.org/post', ['X-Another-Test' => 'Test2']);

        $body = json_decode($response->getBody());
        $this->assertEquals('Test2', $body->form->{'X-Another-Test'});
    }

    public function testCheckErrors()
    {
        $class = new \ReflectionClass(\Incapsula\API\Adapter\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $response = $this->getPsr7JsonResponseForFixture('Adapter/errorResponse.json');

        $this->expectException(\Incapsula\API\Adapter\IncapsulaException::class);
        $method->invokeArgs($this->adapter, [$response]);

        $response = $this->getPsr7JsonResponseForFixture('Adapter/notJson');

        $this->expectException(\Incapsula\API\Adapter\JSONException::class);
        $method->invokeArgs($this->adapter, [$response]);
    }
}
