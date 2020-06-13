<?php

namespace Incapsula\API\Tests\Endpoints\Sites\Performance;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class CacheModeTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Performance\CacheMode($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testGet()
    {
        $this->setAdapter(
            'Endpoints/Site/getCacheMode.json',
            '/api/prov/v1/sites/performance/cache-mode/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->get();

        $this->assertIsString($mode);
        $this->assertEquals('dynamic_and_aggressive', $mode);
    }

    public function testSet()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/cache-mode',
            [
                'site_id' => 12345,
                'cache_mode' => 'static_and_aggressive',
                'dynamic_cache_duration' => '5_min',
                'aggressive_cache_duration' => '1_hr'
            ]
        );

        $result = $this->getEndpoint()->set('static_and_aggressive', '5_min', '1_hr');

        $this->assertTrue($result);
    }
}