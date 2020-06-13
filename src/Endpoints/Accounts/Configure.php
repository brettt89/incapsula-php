<?php

namespace Incapsula\API\Endpoints\Accounts;

use Incapsula\API\Endpoints\Accounts\BaseClass;

class Configure extends BaseClass
{
    public function set(string $param, $value): bool
    {
        $options = [
            'account_id' => $this->getAccountID(),
            'param' => $param,
            'value' => $value
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/configure', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->{$param}) && $this->body->debug_info->{$param} == $value);
    }
}
