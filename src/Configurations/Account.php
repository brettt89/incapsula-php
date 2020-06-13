<?php

namespace Incapsula\API\Configurations;

class Account implements Configuration
{
    private $configs = [];

    public function setEmail(string $email)
    {
        $this->configs['email'] = $email;
    }

    public function setParent(int $parent_id)
    {
        $this->configs['parent_id'] = $parent_id;
    }

    public function setAccountOwner(string $user_name)
    {
        $this->configs['user_name'] = $user_name;
    }

    public function setPlan(int $plan_id)
    {
        $this->configs['plan_id'] = $plan_id;
    }

    public function setIdentifier(string $ref_id)
    {
        $this->configs['ref_id'] = $ref_id;
    }

    public function setAccountName(string $address)
    {
        $this->configs['account_name'] = $address;
    }

    public function setLogLevel(string $level)
    {
        $this->configs['log_level'] = $level;
    }

    public function setLogsAccountID(int $id)
    {
        $this->configs['logs_account_id'] = $id;
    }
    
    public function toArray(): array
    {
        return $this->configs;
    }
}
