<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Adapter\Adapter;
use Incapsula\API\Traits\APITrait;
use Incapsula\API\Configurations\Site as SiteConfig;
use Incapsula\API\Parameters\Pagination;

class Site implements Endpoint
{
    use APITrait;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    //
    // Site Controls
    //

    public function getStatus(int $site_id, string $tests = '')
    {
        $options = ['site_id' => $site_id];
        if (strlen($tests) > 0) $options['tests'] = $tests;

        $query = $this->adapter->request('/api/prov/v1/sites/status', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getEmails(int $site_id)
    {
        $options = ['site_id' => $site_id];

        $query = $this->adapter->request('/api/prov/v1/domain/emails', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->domain_emails;
    }

    //
    // Cache
    //

    public function purgeCache(int $site_id, string $pattern = null, string $tag_names = null): bool
    {
        $options = ['site_id' => $site_id];
        if (isset($pattern)) $options['pattern'] = $pattern;
        if (isset($tag_names)) $options['tag_names'] = $tag_names;

        $query = $this->adapter->request('/api/prov/v1/sites/cache/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getCacheMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/performance/cache-mode/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->cache_mode;
    }

    public function setCacheMode(int $site_id, string $mode, string $dyanmic = null, string $aggressive = null): bool
    {
        $options = [
            'site_id' => $site_id,
            'cache_mode' => $mode
        ];
        if (isset($dyanmic)) $options['dynamic_cache_duration'] = $dyanmic;
        if (isset($aggressive)) $options['aggressive_cache_duration'] = $aggressive;

        $query = $this->adapter->request('/api/prov/v1/sites/performance/cache-mode', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    //
    // Secure Resource
    //

    public function getSecureResourceMode(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/performance/secure-resources/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->secured_resources_mode;
    }

    public function setSecureResourceMode(int $site_id, string $mode): bool
    {
        $options = [
            'site_id' => $site_id,
            'secured_resources_mode' => $mode
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/performance/secure-resources', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    //
    // Secure Resource
    //

    public function getStaleContentSettings(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/performance/stale-content/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setStaleContentSettings(
        int $site_id,
        bool $stale = false,
        string $mode = null,
        int $time = null,
        string $time_unit = null
    ): bool {
        $options = [
            'site_id' => $site_id,
            'serve_stale_content' => $stale
        ];
        if (isset($mode)) $options['stale_content_mode'] = $mode;
        if (isset($time)) $options['time'] = $time;
        if (isset($time_unit)) $options['time_unit'] = $time_unit;

        $query = $this->adapter->request('/api/prov/v1/sites/performance/stale-content', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    //
    // Managed Sites
    //

    public function getSites(int $account_id = null, Pagination $pagination = null): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        if ($pagination !== null) $options[] = $pagination;

        $sites = $this->adapter->request('api/prov/v1/sites/list', $options);

        $this->body = json_decode($sites->getBody());
        return $this->body->sites;
    }

    public function addSite(int $account_id, SiteConfig $site): \stdClass
    {
        $options = array_merge($site->toArray(), ['account_id' => $account_id]);

        $query = $this->adapter->request('/api/prov/v1/sites/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteSite(int $site_id): bool
    {
        $query = $this->adapter->request('/api/prov/v1/sites/delete', ['site_id' => $site_id]);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    //
    // Site Configuration
    //

    public function setConfig(int $site_id, string $param, $value): bool
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param,
            'value' => $value
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/configure', $options);

        $this->body = json_decode($query->getBody());
        return ($this->body->debug_info->{$param} == $value);
    }

    public function setLogLevel(int $site_id, string $level): bool
    {
        $options = [
            'site_id' => $site_id,
            'log_level' => $level
        ];

        $query = $this->adapter->request('/api/prov/v1/sites/setlog', $options);

        $this->body = json_decode($query->getBody());
        return ($this->body->debug_info->log_level == $level);
    }

    //
    // Security
    //

    public function setSecurityConfig(int $site_id, string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $query = $this->adapter->request('/api/prov/v1/sites/configure/security', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setACLConfig(int $site_id, string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $query = $this->adapter->request('/api/prov/v1/sites/configure/acl', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setWhitelist(
        int $site_id,
        string $rule_id,
        int $whitelist_id = null,
        array $options
    ) {
        if ($whitelist_id) {
            $options['whitelist_id'] = $whitelist_id;
        }
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
        ]);

        $query = $this->adapter->request('/api/prov/v1/sites/configure/whitelists', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}
