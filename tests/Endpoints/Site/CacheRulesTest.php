<?php

namespace IncapsulaAPI\Test\Endpoint\Site;

use IncapsulaAPI\Test\Endpoint\TestEndpointInterface;

class CacheRulesTest extends \TestCase implements TestEndpointInterface
{
    private $endpoint;

    public function getEndpoint(): \IncapsulaAPI\Endpoint\EndpointInterface
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \IncapsulaAPI\Endpoint\Site\CacheRules($this->getAdapter());
        }
        return $this->endpoint;
    }
    
    public function testCreateCacheRule()
    {
        $this->setAdapter(
            'Site/Performance/addCacheRule.json',
            '/api/prov/v1/sites/performance/caching-rules/add',
            [
                'site_id' => 12345,
                'name' => 'New Rule',
                'action' => 'HTTP_CACHE_MAKE_STATIC',
                'ttl' => 10,
                'ttl_unit' => 'MINUTES'

            ]
        );

        $result = $this->getEndpoint()->createCacheRule(12345, 'New Rule', 'HTTP_CACHE_MAKE_STATIC', [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertIsInt($result);
        $this->assertEquals(12345, $result);
    }

    public function testDeleteCacheRule()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/caching-rules/delete',
            [
                'site_id' => 12345,
                'rule_id' => 98765
            ]
        );

        $result = $this->getEndpoint()->deleteCacheRule(12345, 98765);

        $this->assertTrue($result);
    }

    public function testModifyCacheRule()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/caching-rules/edit',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'ttl' => 10,
                'ttl_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->modifyCacheRule(12345, 98765, [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertTrue($result);
    }

    public function testEnableCacheRule()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/caching-rules/enable',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'enable' => 'true'
            ]
        );

        $result = $this->getEndpoint()->enableCacheRule(12345, 98765);

        $this->assertTrue($result);
    }

    public function testDisableCacheRule()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/caching-rules/enable',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'enable' => 'false'
            ]
        );

        $result = $this->getEndpoint()->disableCacheRule(12345, 98765);

        $this->assertTrue($result);
    }

    public function testGetCacheRules()
    {
        $this->setAdapter(
            'Site/Performance/listCacheRules.json',
            '/api/prov/v1/sites/performance/caching-rules/list',
            [
                'site_id' => 12345,
                'page_size' => 100,
                'page_num' => 2
            ]
        );

        $result = $this->getEndpoint()->getCacheRules(12345, [
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
}
