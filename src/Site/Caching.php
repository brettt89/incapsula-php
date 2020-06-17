<?php

namespace Incapsula\API\Site;

use Incapsula\API\Endpoint;

class Caching extends Endpoint
{
    public function purgeCache(int $site_id, string $pattern = null, string $tag_names = null): bool
    {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($pattern)) {
            $options['pattern'] = $pattern;
        }
        if (isset($tag_names)) {
            $options['tag_names'] = $tag_names;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/cache/purge', $options);
        return empty((array) $this->body);
    }

    public function purgeHostnameCache(int $site_id, string $host_name): bool
    {
        $options = [
            'site_id' => $site_id,
            'host_name' => $host_name
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/hostname/purge', $options);
        return empty((array) $this->body);
    }

    public function getXRayLink(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/xray/get-link', $options);
        return $this->body->url;
    }

    public function getCacheMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode/get', $options);
        return $this->body->cache_mode;
    }

    public function setCacheMode(
        int $site_id, 
        string $mode,
        string $dyanmic = null,
        string $aggressive = null
    ): bool {
        $options = [
            'site_id' => $site_id,
            'cache_mode' => $mode
        ];

        if (isset($dyanmic)) {
            $options['dynamic_cache_duration'] = $dyanmic;
        }
        if (isset($aggressive)) {
            $options['aggressive_cache_duration'] = $aggressive;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode', $options);
        return empty((array) $this->body);
    }

    public function getSecureResourceMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources/get', $options);
        return $this->body->secured_resources_mode;
    }

    public function setSecureResourceMode(int $site_id, string $mode): bool
    {
        $options = [
            'site_id' => $site_id,
            'secured_resources_mode' => $mode
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources', $options);
        return empty((array) $this->body);
    }

    public function getStaleContentSettings(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content/get', $options);
        return $this->body;
    }

    public function setStaleContentSettings(
        int $site_id,
        bool $serve_stale_content,
        string $stale_content_mode = null,
        int $time = null,
        string $time_unit = null
    ): bool {
        $options = [
            'site_id' => $site_id,
            'serve_stale_content' => $serve_stale_content ? 'true' : 'false'
        ];

        if (isset($stale_content_mode)) {
            $options['stale_content_mode'] = $stale_content_mode;
        }
        if (isset($time)) {
            $options['time'] = $time;
        }
        if (isset($time_unit)) {
            $options['time_unit'] = $time_unit;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content', $options);
        return empty((array) $this->body);
    }

    public function getCache404Settings(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache404', $options);
        return $this->body;
    }

    public function setCache404Settings(
        int $site_id,
        bool $enable,
        int $time = null,
        string $time_unit = null
    ): bool {
        $options = [
            'site_id' => $site_id,
            'enable' => $enable ? 'true' : 'false'
        ];

        if (isset($time)) {
            $options['time'] = $time;
        }
        if (isset($time_unit)) {
            $options['time_unit'] = $time_unit;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache404/modify', $options);
        return empty((array) $this->body);
    }

    public function purgeResources(int $site_id, string $url, string $pattern, bool $all_resource = null): bool
    {
        $options = [
            'site_id' => $site_id,
            'resource_url' => $url,
            'resource_pattern' => $pattern
        ];

        if (isset($all_resource)) {
            $options['should_purge_all_site_resources'] = $all_resource ? 'true' : 'false';
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/purge', $options);
        return empty((array) $this->body);
    }

    public function getAdvancedCacheSettings(int $site_id, string $param)
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced/get', $options);
        return $this->body->value;
    }

    public function setAdvancedCacheSettings(int $site_id, string $param, $value): bool
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param,
            'value' => is_bool($value) ? $value ? 'true' : 'false' : $value
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced', $options);
        return empty((array) $this->body);
    }

    public function getCachedResponseHeaders(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers/get', $options);
        return $this->body;
    }

    public function setCachedResponseHeaders(
        int $site_id,
        string $cache_headers,
        bool $cache_all_headers = null
    ): bool {
        $options = array_merge(isset($cache_all_headers) ? [
            'cache_all_headers' => $cache_all_headers ? 'true' : 'false'
        ] : [
            'cache_headers' => $cache_headers
        ], [
            'site_id' => $site_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers', $options);
        return empty((array) $this->body);
    }

    public function getTagResponseHeader(int $site_id)
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response/get', $options);
        return isset($this->body->header) ? $this->body->header : '';
    }

    public function setTagResponseHeader(int $site_id, string $header): bool
    {
        $options = [
            'site_id' => $site_id,
            'header' => $header
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response', $options);
        return empty((array) $this->body);
    }

    public function enableCacheShield(int $site_id, bool $enable): bool
    {
        $options = [
            'site_id' => $site_id,
            'enable' => $enable
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield/enable', $options);
        return empty((array) $this->body);
    }

    public function isCacheShieldEnabled(int $site_id): bool
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield', $options);
        return $this->body->enabled;
    }
}