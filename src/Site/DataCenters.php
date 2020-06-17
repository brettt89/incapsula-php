<?php

namespace Incapsula\API\Site;

use Incapsula\API\Endpoint;

class DataCenters extends Endpoint
{
    public function addDataCenter(
        int $site_id,
        string $name,
        string $address,
        bool $enabled = null,
        bool $standby = null,
        bool $content = null,
        string $lb_algorithm = null
    ): \stdClass {
        $options = [
            'site_id' => $site_id,
            'name' => $name,
            'server_address' => $address
        ];

        if (isset($enabled)) {
            $options['is_enabled'] = $enabled;
        }
        if (isset($standby)) {
            $options['is_standby'] = $standby;
        }
        if (isset($content)) {
            $options['is_content'] = $content;
        }
        if (isset($lb_algorithm)) {
            $options['lb_algorithm'] = $lb_algorithm;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/add', $options);
        return $this->body;
    }

    public function editDataCenter(
        int $dc_id,
        array $options = []
    ): \stdClass {
        $options = array_merge($options, [
            'dc_id' => $dc_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/edit', $options);
        return $this->body;
    }

    public function resumeDataCenterTraffic(int $site_id): bool
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/resume', $options);
        return empty((array) $this->body);
    }

    public function deleteDataCenter(int $dc_id): \stdClass
    {
        $options = [
            'dc_id' => $dc_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/delete', $options);
        return $this->body;
    }

    public function addServer(int $dc_id, string $address, bool $standby = null): \stdClass
    {
        $options = [
            'dc_id' => $dc_id,
            'server_address' => $address
        ];

        if (isset($standby)) {
            $options['is_standby'] = $standby;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/add', $options);
        return $this->body;
    }

    public function editServer(
        int $server_id,
        array $options
    ): \stdClass {
        $options = array_merge($options, [
            'server_id' => $server_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/edit', $options);
        return $this->body;
    }

    public function deleteServer(int $server_id): \stdClass
    {
        $options = [
            'server_id' => $server_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/delete', $options);
        return $this->body;
    }

    public function listDataCenters(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/list', $options);
        return $this->body;
    }

    public function setDataCenterOriginPOP(int $dc_id, string $origin_pop = null): bool
    {
        $options = [
            'dc_id' => $dc_id
        ];

        if (isset($origin_pop)) {
            $options['origin_pop'] = $origin_pop;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/datacenter/origin-pop/modify', $options);
        return empty((array) $this->body);
    }

    public function getDataCenterRecommendedOriginPOP(int $dc_id): \stdClass
    {
        $options = [
            'dc_id' => $dc_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/datacenter/origin-pop/recommend', $options);
        return $this->body;
    }
}
