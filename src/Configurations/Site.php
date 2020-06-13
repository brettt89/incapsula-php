<?php

namespace Incapsula\API\Configurations;

use Incapsula\API\Interfaces\Configuration;

class Site implements Configuration
{
    private $configs = [];

    public function __construct(string $domain)
    {
        $this->setDomain($domain);
    }

    public function setDomain(string $domain)
    {
        $this->configs['domain'] = $domain;
    }

    public function setAccount(int $account_id)
    {
        $this->configs['account_id'] = $account_id;
    }

    public function setIdentifier(string $ref_id)
    {
        $this->configs['ref_id'] = $ref_id;
    }

    public function enableEmails()
    {
        $this->configs['send_site_setup_emails'] = 'true';
    }

    public function disableEmails()
    {
        $this->configs['send_site_setup_emails'] = 'false';
    }

    public function setOrigin(string $address)
    {
        $this->configs['site_ip'] = $address;
    }

    public function enableSSL()
    {
        $this->configs['force_ssl'] = 'true';
    }

    public function disableSSL()
    {
        $this->configs['force_ssl'] = 'false';
    }

    public function enableSANDomain()
    {
        $this->configs['naked_domain_san'] = 'true';
    }

    public function disalbeSANDomain()
    {
        $this->configs['naked_domain_san'] = 'false';
    }

    public function enableWildcardSAN()
    {
        $this->configs['wildcard_san'] = 'true';
    }

    public function disalbeWildcardSAN()
    {
        $this->configs['wildcard_san'] = 'false';
    }

    public function setLogLevel(string $level)
    {
        $this->configs['log_level'] = $level;
    }

    public function setLogAccount(int $id)
    {
        $this->configs['logs_account_id'] = $id;
    }
    
    public function toArray(): array
    {
        return $this->configs;
    }
}
