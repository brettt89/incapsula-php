<?php

namespace Incapsula\API\Endpoints\Site;

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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/cache/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function purgeHostnameCache(int $site_id, string $host_name): bool
    {
        $options = [
            'site_id' => $site_id,
            'host_name' => $host_name
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/hostname/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getXRayLink(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/xray/get-link', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getCacheMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode/get', $options);

        $this->body = json_decode($query->getBody());
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getSecureResourceMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->secured_resources_mode;
    }

    public function setSecureResourceMode(int $site_id, string $mode): bool
    {
        $options = [
            'site_id' => $site_id,
            'secured_resources_mode' => $mode
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getStaleContentSettings(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setStaleContentSettings(
        int $site_id,
        bool $stale,
        string $mode = null,
        int $time = null,
        string $time_unit = null
    ): bool {
        $options = [
            'site_id' => $site_id,
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

    public function getCache404Settings(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache404', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setCache404Settings(
        int $site_id,
        bool $enable,
        int $time = null,
        string $time_unit = null
    ): \stdClass {
        $options = [
            'site_id' => $site_id,
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

    public function purgeResources(int $site_id, string $url, string $pattern, bool $all_resource = null): bool
    {
        $options = [
            'site_id' => $site_id,
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

    public function getAdvancedCacheSettings(int $site_id, string $param): string
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->{$param};
    }

    public function setAdvancedCacheSettings(int $site_id, string $param, $value): bool
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param,
            'value' => $value
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/advanced', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getCachedResponseHeaders(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setCachedResponseHeaders(int $site_id, string $headers, bool $cache_all = null): bool
    {
        $options = array_merge(isset($cache_all) ? [
            'cache_all_headers' => $cache_all
        ] : [
            'cache_headers' => $headers
        ], [
            'site_id' => $site_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/response-headers', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getTagResponseHeader(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->header;
    }

    public function setTagResponseHeader(int $site_id, string $header): bool
    {
        $options = [
            'site_id' => $site_id,
            'header' => $header
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/tag-response', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function enableCacheShield(int $site_id, bool $enable): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'enabled' => $enable
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield/enable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function isCacheShieldEnabled(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-shield', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}