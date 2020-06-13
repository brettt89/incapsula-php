<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Adapter\Adapter;
use Incapsula\API\Traits\APITrait;
use Incapsula\API\Parameters\Pagination;
use Incapsula\API\Adapter\IncapsulaException;
use Incapsula\API\Configurations\Account as AccountConfig;

class Account implements Endpoint
{
    use APITrait;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    //
    // Account Controls
    //

    public function getStatus(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->adapter->request('/api/prov/v1/account', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->account;
    }

    public function getLoginToken(int $account_id = null): string
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->adapter->request('/api/prov/v1/accounts/gettoken', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->generated_token;
    }

    public function getSubscription(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->adapter->request('/api/prov/v1/accounts/subscription', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    //
    // Managed Accounts
    //

    public function getManagedAccounts(int $account_id = null, Pagination $pagination = null): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        if ($pagination !== null) {
            $options[] = $pagination;
        }

        $query = $this->adapter->request('/api/prov/v1/accounts/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->accounts;
    }

    public function addManagedAccount(string $email, AccountConfig $account): \stdClass
    {
        $options = array_merge($account->toArray(), ['email' => $email]);

        $query = $this->adapter->request('/api/prov/v1/accounts/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->account;
    }

    public function deleteManagedAccount(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/delete', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    //
    // Sub Accounts
    //

    public function getSubAccounts(int $account_id = null, Pagination $pagination = null): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        if ($pagination !== null) {
            $options[] = $pagination;
        }

        $query = $this->adapter->request('/api/prov/v1/accounts/listSubAccounts', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->resultList;
    }

    public function addSubAccount(string $name, AccountConfig $account): \stdClass
    {
        $options = array_merge($account->toArray(), ['sub_account_name' => $name]);

        $query = $this->adapter->request('/api/prov/v1/subaccounts/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->sub_account;
    }

    public function deleteSubAccount(int $sub_account_id): bool
    {
        $options = [
            'sub_account_id' => $sub_account_id
        ];

        $query = $this->adapter->request('/api/prov/v1/subaccounts/delete', $options);

        $this->body = json_decode($query->getBody());
        return true;
    }

    //
    // Configurations
    //

    public function setConfiguration(int $account_id = null, string $param, $value): bool
    {
        if (!isset($value)) {
            throw new \Exception("Value must be provided");
        }

        $options = array_merge(
            [
            'param' => $param,
            'value' => $value
        ],
            $account_id !== null ? ['account_id' => $account_id] : []
        );

        $query = $this->adapter->request('/api/prov/v1/accounts/configure', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->{$param}) && $this->body->debug_info->{$param} == $value);
    }

    //
    // Log Level
    //

    public function setLogLevel(int $account_id, string $level): bool
    {
        $options = [
            'account_id' => $account_id,
            'log_level' => $level
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/setlog', $options);

        $this->body = json_decode($query->getBody());
        return true;
    }

    //
    // Log Storage
    //

    public function testS3Connection(
        int $account_id,
        string $bucket_name,
        string $access_key,
        string $secret_key,
        bool $save_on_success = false
    ): bool {
        $options = [
            'account_id' => $account_id,
            'bucket_name' => $bucket_name,
            'access_key' => $access_key,
            'secret_key' => $secret_key,
            'save_on_success' => $save_on_success
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/testS3Connection', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Connection test succeeded');
    }

    public function testSFTPConnection(
        int $account_id,
        string $host,
        string $user_name,
        string $password,
        string $destination_folder,
        bool $save_on_success = false
    ): bool {
        $options = [
            'account_id' => $account_id,
            'host' => $host,
            'user_name' => $user_name,
            'password' => $password,
            'destination_folder' => $destination_folder,
            'save_on_success' => $save_on_success
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/testSftpConnection', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Connection test succeeded');
    }

    public function setAmazomSiemStorage(
        int $account_id,
        string $bucket_name,
        string $access_key,
        string $secret_key
    ): bool {
        $options = [
            'account_id' => $account_id,
            'bucket_name' => $bucket_name,
            'access_key' => $access_key,
            'secret_key' => $secret_key
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/setAmazonSiemStorage', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }

    public function setSFTPSiemStorage(
        int $account_id,
        string $host,
        string $user_name,
        string $password,
        string $destination_folder
    ): bool {
        $options = [
            'account_id' => $account_id,
            'host' => $host,
            'user_name' => $user_name,
            'password' => $password,
            'destination_folder' => $destination_folder
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/setSftpSiemStorage', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }

    public function setDefaultSiemStorage(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/setDefaultSiemStorage', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }

    //
    // Data Region
    //

    public function getDataStorageRegion(int $account_id): string
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/data-privacy/show', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->region;
    }

    public function setDataStorageRegion(int $account_id, string $region): bool
    {
        $options = [
            'account_id' => $account_id,
            'region' => $region
        ];

        $query = $this->adapter->request('/api/prov/v1/accounts/data-privacy/set-region-default', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}
