<?php

namespace Incapsula\API\Tests\Sites;

use Incapsula\API\Tests\TestAPI;

class CachingTest extends \TestCase implements TestAPI
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\API
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Site\Caching($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testPurgeCache()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/cache/purge',
            [
                'site_id' => 12345,
                'pattern' => 'testPattern',
                'tag_names' => 'test tag,tag2'
            ]
        );

        $result = $this->getEndpoint()->purgeCache(12345, 'testPattern', 'test tag,tag2');

        $this->assertTrue($result);
    }

    public function testPurgeHostnameCache()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/hostname/purge',
            [
                'site_id' => 12345,
                'host_name' => 'www.example.com'
            ]
        );

        $result = $this->getEndpoint()->purgeHostnameCache(12345, 'www.example.com');

        $this->assertTrue($result);
    }

    public function testGetXRayLink()
    {
        $this->setAdapter(
            'Site/Performance/getXRayLink.json',
            '/api/prov/v1/sites/xray/get-link',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getXRayLink(12345);

        $this->assertEquals('http://www.example.com/_Incapsula_Resource?SW_XRAY=0&ticket=hash', $result);
    }

    public function testGetCacheMode()
    {
        $this->setAdapter(
            'Site/getCacheMode.json',
            '/api/prov/v1/sites/performance/cache-mode/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getCacheMode(12345);

        $this->assertIsString($mode);
        $this->assertEquals('dynamic_and_aggressive', $mode);
    }

    public function testSetCacheMode()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/cache-mode',
            [
                'site_id' => 12345,
                'cache_mode' => 'static_and_aggressive',
                'dynamic_cache_duration' => '5_min',
                'aggressive_cache_duration' => '1_hr'
            ]
        );

        $result = $this->getEndpoint()->setCacheMode(12345, 'static_and_aggressive', '5_min', '1_hr');

        $this->assertTrue($result);
    }

    public function testGetSecureResourceMode()
    {
        $this->setAdapter(
            'Site/getSecureResourceMode.json',
            '/api/prov/v1/sites/performance/secure-resources/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getSecureResourceMode(12345);

        $this->assertIsString($mode);
        $this->assertEquals('do_not_cache', $mode);
    }

    public function testSetSecureResourceMode()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/secure-resources',
            [
                'site_id' => 12345,
                'secured_resources_mode' => 'do_not_cache'
            ]
        );

        $result = $this->getEndpoint()->setSecureResourceMode(12345, 'do_not_cache');

        $this->assertTrue($result);
    }

    public function testGetStaleContentSettings()
    {
        $this->setAdapter(
            'Site/getStaleContentSettings.json',
            '/api/prov/v1/sites/performance/stale-content/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getStaleContentSettings(12345);

        $this->assertIsObject($mode);
    }

    public function testSetStaleContentSettings()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/stale-content',
            [
                'site_id' => 12345,
                'serve_stale_content' => 'true',
                'stale_content_mode' => 'ADAPTIVE',
                'time' => 5,
                'time_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->setStaleContentSettings(12345, true, 'ADAPTIVE', 5, 'MINUTES');

        $this->assertTrue($result);
    }

    public function testGetCache404Settings()
    {
        $this->setAdapter(
            'Site/Performance/getCache404.json',
            '/api/prov/v1/sites/performance/cache404',
            [
                'site_id' => 12345
            ]
        );

        $cache404 = $this->getEndpoint()->getCache404Settings(12345);

        $this->assertIsObject($cache404);
        $this->assertEquals(true, $cache404->enabled);
        $this->assertEquals(10, $cache404->time);
    }

    public function testSetCache404Settings()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/cache404/modify',
            [
                'site_id' => 12345,
                'enable' => 'true',
                'time' => 10,
                'time_unit' => 'HOURS'
            ]
        );

        $result = $this->getEndpoint()->setCache404Settings(12345, true, 10, 'HOURS');

        $this->assertTrue($result);
    }

    public function testPurgeResources()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/purge',
            [
                'site_id' => 12345,
                'resource_url' => '/test/url/page.css',
                'resource_pattern' => 'equals',
                'should_purge_all_site_resources' => 'true'
            ]
        );

        $result = $this->getEndpoint()->purgeResources(12345, '/test/url/page.css', 'equals', true);

        $this->assertTrue($result);
    }

    public function testGetAdvancedCacheSettings()
    {
        $this->setAdapter(
            'Site/Performance/getAdvancedCache.json',
            '/api/prov/v1/sites/performance/advanced/get',
            [
                'site_id' => 12345,
                'param' => 'send_age_header'
            ]
        );

        $result = $this->getEndpoint()->getAdvancedCacheSettings(12345, 'send_age_header');

        $this->assertEquals(true, $result);
    }

    public function testSetAdvancedCacheSettings()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/advanced',
            [
                'site_id' => 12345,
                'param' => 'send_age_header',
                'value' => 'false'
            ]
        );

        $result = $this->getEndpoint()->setAdvancedCacheSettings(12345, 'send_age_header', false);

        $this->assertTrue($result);
    }

    public function testGetCachedResponseHeaders()
    {
        $this->setAdapter(
            'Site/Performance/getCachedResponseHeaders.json',
            '/api/prov/v1/sites/performance/response-headers/get',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getCachedResponseHeaders(12345);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('custom_headers', $result);
        $this->assertIsArray($result->custom_headers);
        $this->assertEquals('header1', $result->custom_headers[0]);
        $this->assertEquals('header2', $result->custom_headers[1]);
        $this->assertEquals('header3', $result->custom_headers[2]);
    }

    public function testSetCachedResponseHeaders()
    {
        $this->setAdapter('success.json');
        $this->getAdapter()->expects($this->exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    $this->equalTo('/api/prov/v1/sites/performance/response-headers'),
                    $this->equalTo([
                        'site_id' => 12345,
                        'cache_all_headers' => 'true'
                    ])
                ],
                [
                    $this->equalTo('/api/prov/v1/sites/performance/response-headers'),
                    $this->equalTo([
                        'site_id' => 12345,
                        'cache_headers' => 'test-cache-all'
                    ])
                ]
            );

        $result = $this->getEndpoint()->setCachedResponseHeaders(12345, 'test-cache-all', true);

        $this->assertTrue($result);

        $result = $this->getEndpoint()->setCachedResponseHeaders(12345, 'test-cache-all');

        $this->assertTrue($result);
    }

    public function testGetTagResponseHeader()
    {
        $this->setAdapter(
            'Site/Performance/getTagResponse.json',
            '/api/prov/v1/sites/performance/tag-response/get',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getTagResponseHeader(12345);

        $this->assertIsString($result);
        $this->assertEquals('some_header', $result);
    }

    public function testSetTagResponseHeader()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/tag-response',
            [
                'site_id' => 12345,
                'header' => 'some_header'
            ]
        );

        $result = $this->getEndpoint()->setTagResponseHeader(12345, 'some_header');

        $this->assertTrue($result);
    }

    public function testEnableCacheShield()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/cache-shield/enable',
            [
                'site_id' => 12345,
                'enable' => false
            ]
        );

        $result = $this->getEndpoint()->enableCacheShield(12345, false);

        $this->assertTrue($result);
    }

    public function testIsCacheShieldEnabled()
    {
        $this->setAdapter(
            'Site/Performance/isCacheShieldEnabled.json',
            '/api/prov/v1/sites/performance/cache-shield',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->isCacheShieldEnabled(12345);

        $this->assertTrue($result);
    }
}
