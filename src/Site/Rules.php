<?php

namespace Incapsula\API\Site;

use Incapsula\API\Endpoint;

class Rules extends Endpoint
{
    public function createIncapRule(
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

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/add', $options);
        return $this->body;
    }

    public function modifyIncapRule(
        int $rule_id,
        string $action,
        array $rule_options = []
    ): bool {
        $options = array_merge($rule_options, [
            'rule_id' => $rule_id,
            'action' => $action
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/edit', $options);
        return empty((array) $this->body);
    }

    public function enableIncapRule(int $rule_id): bool
    {
        $options = [
            'rule_id' => $rule_id,
            'enable' => 'true'
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/enableDisable', $options);
        return empty((array) $this->body);
    }

    public function disableIncapRule(int $rule_id): bool
    {
        $options = [
            'rule_id' => $rule_id,
            'enable' => 'false'
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/enableDisable', $options);
        return empty((array) $this->body);
    }

    public function deleteIncapRule(int $rule_id): \stdClass
    {
        $options = [
            'rule_id' => $rule_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/delete', $options);
        return $this->body;
    }

    public function getIncapRules(
        int $site_id,
        array $options = []
    ): \stdClass {
        $options = array_merge($options, [
            'site_id' => $site_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/list', $options);
        return $this->body;
    }

    // @todo Incapsula Error - Disabled
    public function getAccountIncapRules(
        int $account_id,
        array $options = []
    ): \stdClass {
        $options = array_merge($options, [
            'account_id' => $account_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/account/list', $options);
        return $this->body;
    }

    public function modifyIncapRulePriority(int $rule_id, int $priority): \stdClass
    {
        $options = [
            'rule_id' => $rule_id,
            'priority' => $priority
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/priority/set', $options);
        return $this->body;
    }
}