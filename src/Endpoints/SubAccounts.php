<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Parameters\Pagination;
use Incapsula\API\Configurations\Account as AccountConfig;

class SubAccounts extends Endpoint
{
    public function getList(int $account_id = null, Pagination $pagination = null): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        if ($pagination !== null) {
            $options[] = $pagination;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/listSubAccounts', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->resultList;
    }
    
    public function add(string $name, AccountConfig $account): \stdClass
    {
        $options = array_merge($account->toArray(), ['sub_account_name' => $name]);

        $query = $this->getAdapter()->request('/api/prov/v1/subaccounts/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->sub_account;
    }

    public function delete(int $sub_account_id): bool
    {
        $options = [
            'sub_account_id' => $sub_account_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/subaccounts/delete', $options);

        $this->body = json_decode($query->getBody());
        return true;
    }
}
