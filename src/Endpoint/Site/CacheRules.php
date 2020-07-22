<?php

namespace IncapsulaAPI\Endpoint\Site;

use IncapsulaAPI\Endpoint\Endpoint;

class CacheRules extends Endpoint
{
    public function createCacheRule(int $site_id, string $name, string $action, array $rule_options = []): int
    {
        $options = array_merge($rule_options, [
            'site_id' => $site_id,
            'name' => $name,
            'action' => $action
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/add', $options);
        return $this->body->rule_id;
    }

    public function deleteCacheRule(int $site_id, int $rule_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/delete', $options);
        return empty((array) $this->body);
    }

    public function modifyCacheRule(int $site_id, int $rule_id, array $rule_options = []): bool
    {
        $options = array_merge($rule_options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/edit', $options);
        return empty((array) $this->body);
    }

    public function enableCacheRule(int $site_id, int $rule_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
            'enable' => 'true'
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/enable', $options);
        return empty((array) $this->body);
    }

    public function disableCacheRule(int $site_id, int $rule_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
            'enable' => 'false'
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/enable', $options);
        return empty((array) $this->body);
    }

    public function getCacheRules(int $site_id, $pagination_options = []): \stdClass
    {
        $options = array_merge($pagination_options, [
            'site_id' => $site_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/list', $options);
        return $this->body;
    }
}
