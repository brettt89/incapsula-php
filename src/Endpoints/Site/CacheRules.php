<?php

namespace Incapsula\API\Endpoints\Site;

use Incapsula\API\Endpoint;

class CacheRules extends Endpoint
{   
    public function addCacheRule(int $site_id, string $name, string $action, array $rule_options = []): \stdClass
    {
        $options = array_merge($rule_options, [
            'site_id' => $site_id,
            'name' => $name,
            'action' => $action
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteCacheRule(int $site_id, int $rule_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/delete', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function setCacheRule(int $site_id, int $rule_id, array $rule_options = []): \stdClass
    {
        $options = array_merge($rule_options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function enableCacheRule(int $site_id, int $rule_id, bool $enabled): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
            'enable' => $enabled
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/enable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listCacheRules(int $site_id, $pagination_options = null): \stdClass
    {
        $options = array_merge($pagination_options, [
            'site_id' => $site_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}