<?php

namespace Incapsula\API\Endpoints;

class Accounts extends Endpoint
{
    public function getStatus(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->getAdapter()->request('/api/prov/v1/account', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->account;
    }

    public function getToken(int $account_id = null): string
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/gettoken', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->generated_token;
    }

    public function getSubscription(int $account_id = null): \stdClass
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/subscription', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    //
    // Managed Accounts
    //

    public function getList(int $account_id = null, array $pagination_options = []): array
    {
        $options = $account_id !== null ? ['account_id' => $account_id] : [];
        $options = array_merge($pagination_options, $options);

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->accounts;
    }

    public function add(string $email, array $account_options = []): \stdClass
    {
        $options = array_merge($account_options, ['email' => $email]);

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->account;
    }

    public function delete(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/delete', $options);

        $this->body = json_decode($query->getBody());
        return true;
    }

    //
    // Logs
    //

    public function setLogLevel(int $account_id, string $level): bool
    {
        $options = [
            'account_id' => $account_id,
            'log_level' => $level
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/setlog', $options);

        $this->body = json_decode($query->getBody());
        return true;
    }

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

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/testS3Connection', $options);

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

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/testSftpConnection', $options);

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

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/setAmazonSiemStorage', $options);

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

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/setSftpSiemStorage', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }

    public function setDefaultSiemStorage(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/accounts/setDefaultSiemStorage', $options);

        $this->body = json_decode($query->getBody());
        return (isset($this->body->debug_info->message) && $this->body->debug_info->message == 'Configuration was successfully updated');
    }
}
