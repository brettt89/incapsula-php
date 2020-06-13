<?php

namespace Incapsula\API\Tests\Parameters;

class AuthTest extends \TestCase
{
    public function testGetRequestParameters()
    {
        $auth    = new \Incapsula\API\Parameters\Auth('123456789', 'abcdefghijklymnop-123456789');
        $parameters = $auth->getRequestParameters();

        $this->assertArrayHasKey('api_id', $parameters);
        $this->assertArrayHasKey('api_key', $parameters);

        $this->assertEquals('123456789', $parameters['api_id']);
        $this->assertEquals('abcdefghijklymnop-123456789', $parameters['api_key']);

        $this->assertCount(2, $parameters);
    }
}