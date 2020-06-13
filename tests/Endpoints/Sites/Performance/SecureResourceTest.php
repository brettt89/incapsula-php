<?php

namespace Incapsula\API\Tests\Endpoints\Sites\Performance;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class SecureResourceTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Performance\SecureResource($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testGet()
    {
        $this->setAdapter(
            'Endpoints/Site/getSecureResourceMode.json',
            '/api/prov/v1/sites/performance/secure-resources/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->get();

        $this->assertIsString($mode);
        $this->assertEquals('do_not_cache', $mode);
    }

    public function testSet()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/secure-resources',
            [
                'site_id' => 12345,
                'secured_resources_mode' => 'do_not_cache'
            ]
        );

        $result = $this->getEndpoint()->set('do_not_cache');

        $this->assertTrue($result);
    }
}