<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Sites\BaseClass;

class Configure extends BaseClass
{
    public function set(string $param, $value): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'param' => $param,
            'value' => $value
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/configure', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->{$param}) && $this->body->debug_info->{$param} == $value);
    }

    public function setSecurity(string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/configure/security', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setACL(string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/configure/acl', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setWhitelist(
        string $rule_id,
        int $whitelist_id = null,
        array $options
    ) {
        $options = array_merge($options, [
            'site_id' => $this->getSiteID(),
            'rule_id' => $rule_id,
        ]);

        if (isset($whitelist_id)) {
            $options['whitelist_id'] = $whitelist_id;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/configure/whitelists', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}
