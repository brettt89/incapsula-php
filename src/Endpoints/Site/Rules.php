<?php

namespace Incapsula\API\Endpoints\Site;

use Incapsula\API\Endpoint;

class Rules extends Endpoint
{
    public function addIncapRule(
        int $site_id,
        string $name,
        string $action,
        array $rule_options = []
    ): \stdClass {
        $options = array_merge($rule_options, [
            'site_id' => $site_id,
            'name' => $name,
            'action' => $action
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function updateIncapRule(
        int $rule_id,
        string $action,
        array $rule_options = []
    ): \stdClass {
        $options = array_merge($rule_options, [
            'rule_id' => $rule_id,
            'action' => $action
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function enableDisableIncapRule(int $rule_id, bool $enable): \stdClass
    {
        $options = [
            'rule_id' => $rule_id,
            'enabled' => $enable
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/enableDisable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteIncapRule(int $rule_id): \stdClass
    {
        $options = [
            'rule_id' => $rule_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/delete', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listIncapRules(
        int $site_id,
        bool $inc_delivery = null,
        bool $inc_incap = null,
        array $pagination_options = []
    ): \stdClass {
        $options = array_merge($pagination_options, [
            'site_id' => $site_id
        ]);

        if (isset($inc_delivery)) {
            $options['include_ad_rules'] = $inc_delivery;
        }
        if (isset($inc_incap)) {
            $options['include_incap_rules'] = $inc_incap;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listAccountIncapRules(
        int $account_id,
        bool $inc_delivery = null,
        bool $inc_incap = null
    ): \stdClass {
        $options = [
            'account_id' => $account_id
        ];

        if (isset($inc_delivery)) {
            $options['include_ad_rules'] = $inc_delivery;
        }
        if (isset($inc_incap)) {
            $options['include_incap_rules'] = $inc_incap;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/account/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setIncapRulePriority(int $rule_id, int $priority): \stdClass
    {
        $options = [
            'rule_id' => $rule_id,
            'priority' => $priority
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/priority/set', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}