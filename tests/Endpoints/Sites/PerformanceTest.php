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

    public function testPurgeResource()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/purge',
            [
                'site_id' => 12345,
                'resource_url' => '/test/url/page.css',
                'resource_pattern' => 'equals',
                'should_purge_all_site_resources' => true
            ]
        );

        $result = $this->getEndpoint()->purgeResource('/test/url/page.css', 'equals', true);

        $this->assertTrue($result);
    }

    public function testGetAdvancedCache()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/getAdvancedCache.json',
            '/api/prov/v1/sites/performance/advanced/get',
            [
                'site_id' => 12345,
                'param' => 'send_age_header'
            ]
        );

        $result = $this->getEndpoint()->getAdvancedCache('send_age_header');

        $this->assertIsString($result);
        $this->assertEquals(true, $result);
    }

    public function testSetAdvancedCache()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/advanced',
            [
                'site_id' => 12345,
                'param' => 'send_age_header',
                'value' => false
            ]
        );

        $result = $this->getEndpoint()->setAdvancedCache('send_age_header', false);

        $this->assertTrue($result);
    }

    public function testGetCachedResponseHeaders()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/getCachedResponseHeaders.json',
            '/api/prov/v1/sites/performance/response-headers/get',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getCachedResponseHeaders();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('custom_headers', $result);
        $this->assertIsArray($result->custom_headers);
        $this->assertEquals('header1', $result->custom_headers[0]);
        $this->assertEquals('header2', $result->custom_headers[1]);
        $this->assertEquals('header3', $result->custom_headers[2]);
    }

    public function testSetCachedResponseHeaders()
    {
        $this->setAdapter('Endpoints/success.json');
        $this->getAdapter()->expects($this->exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    $this->equalTo('/api/prov/v1/sites/performance/response-headers'),
                    $this->equalTo([
                        'site_id' => 12345,
                        'cache_all_headers' => true
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

        $result = $this->getEndpoint()->setCachedResponseHeaders('test-cache-all', true);

        $this->assertTrue($result);

        $result = $this->getEndpoint()->setCachedResponseHeaders('test-cache-all');

        $this->assertTrue($result);
    }

    public function testGetTagResponse()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/getTagResponse.json',
            '/api/prov/v1/sites/performance/tag-response/get',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getTagResponse();

        $this->assertIsString($result);
        $this->assertEquals('some_header', $result);
    }

    public function testSetTagResponse()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/tag-response',
            [
                'site_id' => 12345,
                'header' => 'some_header'
            ]
        );

        $result = $this->getEndpoint()->setTagResponse('some_header');

        $this->assertTrue($result);
    }

    public function testAddCacheRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/caching-rules/add',
            [
                'site_id' => 12345,
                'name' => 'New Rule',
                'action' => 'HTTP_CACHE_MAKE_STATIC',
                'ttl' => 10,
                'ttl_unit' => 'MINUTES'

            ]
        );

        $result = $this->getEndpoint()->addCacheRule('New Rule', 'HTTP_CACHE_MAKE_STATIC', [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertIsObject($result);
    }

    public function testDeleteCacheRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/caching-rules/delete',
            [
                'site_id' => 12345,
                'rule_id' => 98765
            ]
        );

        $result = $this->getEndpoint()->deleteCacheRule(98765);

        $this->assertTrue($result);
    }

    public function testSetCacheRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/caching-rules/edit',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'ttl' => 10,
                'ttl_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->setCacheRule(98765, [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertIsObject($result);
    }

    public function testEnableCacheRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/caching-rules/enable',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'enable' => false
            ]
        );

        $result = $this->getEndpoint()->enableCacheRule(98765, false);

        $this->assertIsObject($result);
    }

    public function testListCacheRules()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/listCacheRules.json',
            '/api/prov/v1/sites/performance/caching-rules/list',
            [
                'site_id' => 12345,
                'page_size' => 100,
                'page_num' => 2
            ]
        );

        $result = $this->getEndpoint()->listCacheRules([
            'page_size' => 100,
            'page_num' => 2
        ]);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('HTTP_CACHE_DIFFERENTIATE_BY_GEO', $result);
        $this->assertIsArray($result->HTTP_CACHE_DIFFERENTIATE_BY_GEO);
        $this->assertEquals('3750', $result->HTTP_CACHE_DIFFERENTIATE_BY_GEO[0]->id);
        $this->assertEquals('0', $result->HTTP_CACHE_DIFFERENTIATE_BY_GEO[0]->hits);
        $this->assertEquals('HTTP_CACHE_DIFFERENTIATE_BY_GEO', $result->HTTP_CACHE_DIFFERENTIATE_BY_GEO[0]->action);
    }

    public function testGetRewritePort()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/getRewritePort.json',
            '/api/prov/v1/sites/performance/rewrite-port',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getRewritePort();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('port', $result);
        $this->assertIsObject($result->port);
        $this->assertEquals('80', $result->port->from);
        $this->assertEquals('8080', $result->port->to);
    }

    public function testSetRewritePort()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/rewrite-port/modify',
            [
                'site_id' => 12345,
                'rewrite_port_enabled' => true,
                'port' => 8080,
                'rewrite_ssl_port_enabled' => true,
                'ssl_port' => 444
            ]
        );

        $result = $this->getEndpoint()->setRewritePort(true, 8080, true, 444);

        $this->assertIsObject($result);
    }

    public function testGetErrorPage()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/error-page',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getErrorPage();

        $this->assertIsObject($result);
    }

    public function testSetErrorPage()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/error-page/modify',
            [
                'site_id' => 12345,
                'error_page_template' => '<html><body></body></html>'
            ]
        );

        $result = $this->getEndpoint()->setErrorPage('<html><body></body></html>');

        $this->assertIsObject($result);
    }

    public function testEnableCacheShield()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/cache-shield/enable',
            [
                'site_id' => 12345,
                'enabled' => false
            ]
        );

        $result = $this->getEndpoint()->enableCacheShield(false);

        $this->assertIsObject($result);
    }

    public function testIsCacheShieldEnabled()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/cache-shield',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->isCacheShieldEnabled();

        $this->assertIsObject($result);
    }
}
