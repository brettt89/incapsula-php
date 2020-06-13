<?php

namespace Incapsula\API\Endpoints\Sites\Performance;

use Incapsula\API\Endpoints\Sites\BaseClass;

class SecureResource extends BaseClass
{
    public function get(): string
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->secured_resources_mode;
    }

    public function set(string $mode): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'secured_resources_mode' => $mode
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/secure-resources', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}