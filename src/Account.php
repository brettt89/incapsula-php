<?php

namespace Incapsula\API;

use Incapsula\API\Endpoint;

class Account extends Endpoint
{
    // Account Controls
    // Manual Testing completed (Success)
    //
    
    public function getStatus(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $this->body = $this->getAdapter()->request('/api/prov/v1/account', $options);
        return $this->body->account;
    }

    public function getLoginToken(int $account_id = null): string
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/gettoken', $options);
        return $this->body->generated_token;
    }

    public function getSubscriptionDetails(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/subscription', $options);
        return $this->body;
    }

    //
    // Managed Accounts
    // @todo Manual Testing required (Account features disabled)
    //

    public function getManagedAccounts(int $account_id = null, array $pagination_options = []): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        $options = array_merge($pagination_options, $options);

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/list', $options);
        return $this->body->accounts;
    }

    public function createManagedAccount(string $email, array $account_options = []): \stdClass
    {
        $options = array_merge($account_options, ['email' => $email]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/add', $options);
        return $this->body->account;
    }

    public function deleteManagedAccount(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/delete', $options);
        return true;
    }

    //
    // Sub Accounts
    // Manual Testing completed (Success)
    //

    public function getSubAccounts(int $account_id = null, $pagination_options = []): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        $options = array_merge($pagination_options, $options);

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/listSubAccounts', $options);
        return $this->body->resultList;
    }
    
    public function createSubAccount(string $name, array $account_options = []): \stdClass
    {
        $options = array_merge($account_options, ['sub_account_name' => $name]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/subaccounts/add', $options);
        return $this->body->sub_account;
    }

    public function deleteSubAccount(int $sub_account_id): bool
    {
        $options = [
            'sub_account_id' => $sub_account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/subaccounts/delete', $options);
        return empty((array) $this->body);
    }

    //
    // Logging Addon
    // @todo Manual Testing required (Feature not enabled)
    //

    public function modifyLogLevel(int $account_id, string $level): bool
    {
        $options = [
            'account_id' => $account_id,
            'log_level' => $level
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/setlog', $options);
        return (isset($this->body->debug_info->log_level) && $this->body->debug_info->log_level == $level);
    }

    public function testS3Connection(
        int $account_id,
        string $bucket_name,
        string $access_key,
        string $secret_key,
        bool $save_on_success = false
    ) {
        $options = [
            'account_id' => $account_id,
            'bucket_name' => $bucket_name,
            'access_key' => $access_key,
            'secret_key' => $secret_key,
            'save_on_success' => $save_on_success
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/testS3Connection', $options);
        return $this->body;
    }

    public function testSFTPConnection(
        int $account_id,
        string $host,
        string $user_name,
        string $password,
        string $destination_folder,
        bool $save_on_success = false
    ) {
        $options = [
            'account_id' => $account_id,
            'host' => $host,
            'user_name' => $user_name,
            'password' => $password,
            'destination_folder' => $destination_folder,
            'save_on_success' => $save_on_success
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/testSftpConnection', $options);
        return $this->body;
    }

    public function modifyAmazomSiemStorage(
        int $account_id,
        string $bucket_name,
        string $access_key,
        string $secret_key
    ): \stdClass {
        $options = [
            'account_id' => $account_id,
            'bucket_name' => $bucket_name,
            'access_key' => $access_key,
            'secret_key' => $secret_key
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/setAmazonSiemStorage', $options);
        return $this->body;
    }

    public function modifySFTPSiemStorage(
        int $account_id,
        string $host,
        string $user_name,
        string $password,
        string $destination_folder
    ): \stdClass {
        $options = [
            'account_id' => $account_id,
            'host' => $host,
            'user_name' => $user_name,
            'password' => $password,
            'destination_folder' => $destination_folder
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/setSftpSiemStorage', $options);
        return $this->body;
    }

    public function modifyDefaultSiemStorage(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/setDefaultSiemStorage', $options);

        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }

    //
    // Configuration
    // Manual Testing completed (Success)
    //

    public function modifyConfig(int $account_id, string $param, $value): bool
    {
        $options = [
            'account_id' => $account_id,
            'param' => $param,
            'value' => $value
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/configure', $options);
        return empty((array) $this->body);
    }
    
    public function getDefaultStorageRegion(int $account_id): string
    {
        $options = [
            'account_id' => $account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/data-privacy/show', $options);
        return $this->body->region;
    }

    public function modifyDefaultStorageRegion(int $account_id, string $region): bool
    {
        $options = [
            'account_id' => $account_id,
            'data_storage_region' => $region
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/accounts/data-privacy/set-region-default', $options);
        return (isset($this->body->region) && $this->body->region == $region);
    }
}
