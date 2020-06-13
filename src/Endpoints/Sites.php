<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Configurations\Site as SiteConfig;
use Incapsula\API\Parameters\Pagination;

class Sites extends Endpoint
{
    public function getStatus(int $site_id, string $tests = '')
    {
        $options = ['site_id' => $site_id];
        if (strlen($tests) > 0) {
            $options['tests'] = $tests;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/status', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getList(int $account_id = null, Pagination $pagination = null): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        if ($pagination !== null) {
            $options[] = $pagination;
        }

        $sites = $this->getAdapter()->request('api/prov/v1/sites/list', $options);

        $this->body = json_decode($sites->getBody());
        return $this->body->sites;
    }

    public function add(int $account_id, SiteConfig $site): \stdClass
    {
        $options = array_merge($site->toArray(), ['account_id' => $account_id]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function delete(int $site_id): bool
    {
        $query = $this->getAdapter()->request('/api/prov/v1/sites/delete', ['site_id' => $site_id]);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function setLogLevel(int $site_id, string $level): bool
    {
        $options = [
            'site_id' => $site_id,
            'log_level' => $level
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/setlog', $options);

        $this->body = json_decode($query->getBody());
        return ($this->body->debug_info->log_level == $level);
    }
}
