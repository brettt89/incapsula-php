<?php

namespace Incapsula\API\Endpoints\Accounts;

use Incapsula\API\Endpoints\Accounts\BaseClass;

class DataPrivacy extends BaseClass
{
    public function getDefault(): string
    {
        $options = [
            'account_id' => $this->getAccountID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/data-privacy/show', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->region;
    }

    public function setDefault(string $region): bool
    {
        $options = [
            'account_id' => $this->getAccountID(),
            'region' => $region
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/data-privacy/set-region-default', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}
