<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Endpoint;

class DataCenter extends Endpoint
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function editDataCenter(
        int $dc_id,
        string $name = null,
        bool $enabled = null,
        bool $standby = null,
        bool $content = null
    ): \stdClass {
        $options = [
            'dc_id' => $dc_id
        ];

        if (isset($name)) {
            $options['name'] = $name;
        }
        if (isset($enabled)) {
            $options['is_enabled'] = $enabled;
        }
        if (isset($standby)) {
            $options['is_standby'] = $standby;
        }
        if (isset($content)) {
            $options['is_content'] = $content;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function resumeDataCenterTraffic(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/resume', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteDataCenter(int $dc_id): \stdClass
    {
        $options = [
            'dc_id' => $dc_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/delete', $options);

        $this->body = json_decode($query->getBody());
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function editServer(
        int $server_id,
        string $address = null,
        bool $enabled = null,
        bool $standby = null
    ): \stdClass {
        $options = [
            'server_id' => $server_id
        ];

        if (isset($address)) {
            $options['server_address'] = $address;
        }
        if (isset($enabled)) {
            $options['is_enabled'] = $enabled;
        }
        if (isset($standby)) {
            $options['is_standby'] = $standby;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteServer(int $server_id): \stdClass
    {
        $options = [
            'server_id' => $server_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/servers/delete', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listDataCenters(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/dataCenters/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setDataCenterOriginPOP(int $dc_id, string $origin_pop = null): \stdClass
    {
        $options = [
            'dc_id' => $dc_id
        ];

        if (isset($origin_pop)) {
            $options['origin_pop'] = $origin_pop;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/datacenter/origin-pop/modify', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getDataCenterRecommendedOriginPOP(int $dc_id): \stdClass
    {
        $options = [
            'dc_id' => $dc_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/datacenter/origin-pop/recommend', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}
