<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Sites\BaseClass;

class Performance extends BaseClass
{
    public function getCacheMode(): string
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->cache_mode;
    }

    public function setCacheMode(
        string $mode,
        string $dyanmic = null,
        string $aggressive = null
    ): bool {
        $options = [
            'site_id' => $this->getSiteID(),
            'cache_mode' => $mode
        ];

        if (isset($dyanmic)) {
            $options['dynamic_cache_duration'] = $dyanmic;
        }
        if (isset($aggressive)) {
            $options['aggressive_cache_duration'] = $aggressive;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getSecureResource(): string
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->secured_resources_mode;
    }

    public function setSecureResource(string $mode): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'secured_resources_mode' => $mode
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getStaleContent(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setStaleContent(
        bool $stale,
        string $mode = null,
        int $time = null,
        string $time_unit = null
    ): bool {
        $options = [
            'site_id' => $this->getSiteID(),
            'serve_stale_content' => $stale
        ];

        if (isset($mode)) {
            $options['stale_content_mode'] = $mode;
        }
        if (isset($time)) {
            $options['time'] = $time;
        }
        if (isset($time_unit)) {
            $options['time_unit'] = $time_unit;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getCache404(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache404', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setCache404(
        bool $enable,
        int $time = null,
        string $time_unit = null
    ): \stdClass {
        $options = [
            'site_id' => $this->getSiteID(),
            'enabled' => $enable
        ];

        if (isset($time)) {
            $options['time'] = $time;
        }
        if (isset($time_unit)) {
            $options['time_unit'] = $time_unit;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache404/modify', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function purgeResource(string $url, string $pattern, bool $all_resource = null): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'resource_url' => $url,
            'resource_pattern' => $pattern
        ];

        if (isset($all_resource)) {
            $options['should_purge_all_site_resources'] = $all_resource;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getAdvancedCache(string $param): string
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'param' => $param
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->{$param};
    }

    public function setAdvancedCache(string $param, $value): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'param' => $param,
            'value' => $value
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getCachedResponseHeaders(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setCachedResponseHeaders(string $headers, bool $cache_all = null): bool
    {
        $options = array_merge(isset($cache_all) ? [
            'cache_all_headers' => $cache_all
        ] : [
            'cache_headers' => $headers
        ], [
            'site_id' => $this->getSiteID()
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getTagResponse(): string
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->header;
    }

    public function setTagResponse(string $header): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'header' => $header
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function addCacheRule(string $name, string $action, array $rule_options = []): \stdClass
    {
        $options = array_merge($rule_options, [
            'site_id' => $this->getSiteID(),
            'name' => $name,
            'action' => $action
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteCacheRule(int $rule_id): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/delete', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function setCacheRule(int $rule_id, array $rule_options = []): \stdClass
    {
        $options = array_merge($rule_options, [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function enableCacheRule(int $rule_id, bool $enabled): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id,
            'enable' => $enabled
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/enable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listCacheRules($pagination_options = null): \stdClass
    {
        $options = [ 'site_id' => $this->getSiteID() ];
        $options = isset($pagination_options) ? array_merge($options, $pagination_options) : $options;

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/caching-rules/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getRewritePort(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/rewrite-port', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setRewritePort(
        bool $enabled = null,
        int $port = null,
        bool $ssl_enabled = null,
        int $ssl_port = null
    ): \stdClass {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        if (isset($enabled)) {
            $options['rewrite_port_enabled'] = $enabled;
        }
        if (isset($port)) {
            $options['port'] = $port;
        }
        if (isset($ssl_enabled)) {
            $options['rewrite_ssl_port_enabled'] = $ssl_enabled;
        }
        if (isset($ssl_port)) {
            $options['ssl_port'] = $ssl_port;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/rewrite-port/modify', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getErrorPage(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/error-page', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setErrorPage(string $template): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'error_page_template' => $template
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/error-page/modify', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function enableCacheShield(bool $enable): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'enabled' => $enable
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield/enable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function isCacheShieldEnabled(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}
