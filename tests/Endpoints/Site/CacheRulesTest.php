<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class CacheRulesTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Site\CacheRules($this->getAdapter());
        }
        return $this->endpoint;
    }
    
    public function testAddCacheRule()
    {
        $this->setAdapter(
            'Endpoints/Site/Performance/addCacheRule.json',
            '/api/prov/v1/sites/performance/caching-rules/add',
            [
                'site_id' => 12345,
                'name' => 'New Rule',
                'action' => 'HTTP_CACHE_MAKE_STATIC',
                'ttl' => 10,
                'ttl_unit' => 'MINUTES'

            ]
        );

        $result = $this->getEndpoint()->addCacheRule(12345, 'New Rule', 'HTTP_CACHE_MAKE_STATIC', [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertIsInt($result);
        $this->assertEquals(12345, $result);
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

        $result = $this->getEndpoint()->deleteCacheRule(12345, 98765);

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

        $result = $this->getEndpoint()->setCacheRule(12345, 98765, [
            'ttl' => 10,
            'ttl_unit' => 'MINUTES'
        ]);

        $this->assertTrue($result);
    }

    public function testEnableCacheRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/caching-rules/enable',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'enable' => 'false'
            ]
        );

        $result = $this->getEndpoint()->enableCacheRule(12345, 98765, false);

        $this->assertTrue($result);
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

        $result = $this->getEndpoint()->listCacheRules(12345, [
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
