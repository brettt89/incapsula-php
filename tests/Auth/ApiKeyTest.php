<?php

namespace IncapsulaAPI\Test\Auth;

class ApiKeyTest extends \TestCase
{
    public function testGetRequestParameters()
    {
        $auth    = new \IncapsulaAPI\Auth\ApiKey('123456789', 'abcdefghijklymnop-123456789');
        $parameters = $auth->toArray();

        $this->assertArrayHasKey('api_id', $parameters);
        $this->assertArrayHasKey('api_key', $parameters);

        $this->assertEquals('123456789', $parameters['api_id']);
        $this->assertEquals('abcdefghijklymnop-123456789', $parameters['api_key']);

        $this->assertCount(2, $parameters);
    }
}
