<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class PerformanceTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Performance($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testGetCacheMode()
    {
        $this->setAdapter(
            'Endpoints/Site/getCacheMode.json',
            '/api/prov/v1/sites/performance/cache-mode/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getCacheMode();

        $this->assertIsString($mode);
        $this->assertEquals('dynamic_and_aggressive', $mode);
    }

    public function testSetCacheMode()
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

        $result = $this->getEndpoint()->setCacheMode('static_and_aggressive', '5_min', '1_hr');

        $this->assertTrue($result);
    }

    public function testGetSecureResource()
    {
        $this->setAdapter(
            'Endpoints/Site/getSecureResourceMode.json',
            '/api/prov/v1/sites/performance/secure-resources/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getSecureResource();

        $this->assertIsString($mode);
        $this->assertEquals('do_not_cache', $mode);
    }

    public function testSetSecureResource()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/secure-resources',
            [
                'site_id' => 12345,
                'secured_resources_mode' => 'do_not_cache'
            ]
        );

        $result = $this->getEndpoint()->setSecureResource('do_not_cache');

        $this->assertTrue($result);
    }

    public function testGetStaleContent()
    {
        $this->setAdapter(
            'Endpoints/Site/getStaleContentSettings.json',
            '/api/prov/v1/sites/performance/stale-content/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getStaleContent();

        $this->assertIsObject($mode);
    }

    public function testSetStaleContent()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/stale-content',
            [
                'site_id' => 12345,
                'serve_stale_content' => true,
                'stale_content_mode' => 'ADAPTIVE',
                'time' => 5,
                'time_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->setStaleContent(true, 'ADAPTIVE', 5, 'MINUTES');

        $this->assertTrue($result);
    }

    public function testGetCache404()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/getCache404.json',
            '/api/prov/v1/sites/performance/cache404',
            [
                'site_id' => 12345
            ]
        );

        $cache404 = $this->getEndpoint()->getCache404();

        $this->assertIsObject($cache404);
        $this->assertEquals(true, $cache404->enabled);
        $this->assertEquals(10, $cache404->time);
    }

    public function testSetCache404()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/cache404/modify',
            [
                'site_id' => 12345,
                'enabled' => true,
                'time' => 10,
                'time_unit' => 'HOURS'
            ]
        );

        $result = $this->getEndpoint()->setCache404(true, 10, 'HOURS');

        $this->assertIsObject($result);
    }
}