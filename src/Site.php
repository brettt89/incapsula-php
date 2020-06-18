<?php

namespace Incapsula\API;

use Incapsula\API\Endpoint;

class Site extends Endpoint
{
    public function getStatus(int $site_id, string $tests = null)
    {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($tests)) {
            $options['tests'] = $tests;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/status', $options);
        return $this->body;
    }

    public function getSites(int $account_id = null, array $pagination_options = []): array
    {
        $options = $account_id !== null ? [
            'account_id' => $account_id
        ] : [];

        $this->body = $this->getAdapter()->request('api/prov/v1/sites/list', $options, $pagination_options);
        return $this->body->sites;
    }

    public function createSite(string $domain, array $options): \stdClass
    {
        $options = array_merge($options, [
            'domain' => $domain
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/add', $options);
        return $this->body;
    }

    public function deleteSite(int $site_id): bool
    {
        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/delete', ['site_id' => $site_id]);
        return empty((array) $this->body);
    }

    // @todo - Error from Incapsula - "Invalid configuration parameter name"
    public function modifyLogLevel(int $site_id, string $level): bool
    {
        $options = [
            'site_id' => $site_id,
            'log_level' => $level
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/setlog', $options);
        return ($this->body->debug_info->log_level == $level);
    }

    // @todo - Error from Incapsula - "Invalid configuration parameter name"
    public function enableSupportTLS(int $site_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'support_all_tls_versions' => true
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/tls', $options);
        return empty((array) $this->body);
    }

    // @todo - Error from Incapsula - "Invalid configuration parameter name"
    public function disableSupportTLS(int $site_id): bool
    {
        $options = [
            'site_id' => $site_id,
            'support_all_tls_versions' => false
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/tls', $options);
        return empty((array) $this->body);
    }

    public function getReport(
        int $site_id,
        string $report,
        string $format,
        string $time_range,
        array $time_options = []
    ): \stdClass {
        $options = array_merge($time_options, [
            'site_id' => $site_id,
            'report' => $report,
            'format' => $format,
            'time_range' => $time_range
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/report', $options);
        return $this->body;
    }

    public function getGeeTest(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/geetest-level', $options);
        return $this->body->challenge_algorithm;
    }

    // @todo - Error from Incapsula - "Operation not allowed"
    public function modifyGeeTest(int $site_id, string $algorithm): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'challenge_algorithm' => $algorithm
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/geetest-level/modify', $options);
        return $this->body;
    }

    public function uploadCustomCertificate(
        int $site_id,
        string $crt_base64,
        string $key_base64 = null,
        string $passphrase = null
    ): bool {
        $options = [
            'site_id' => $site_id,
            'certificate' => $crt_base64
        ];

        if (isset($key_base64)) {
            $options['private_key'] = $key_base64;
        }
        if (isset($passphrase)) {
            $options['passphrase'] = $passphrase;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/upload', $options);
        return empty((array) $this->body);
    }

    public function deleteCustomCertificate(int $site_id, string $host_name): bool
    {
        $options = [
            'site_id' => $site_id,
            'host_name' => $host_name
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/remove', $options);
        return empty((array) $this->body);
    }

    public function createCSR(
        int $site_id,
        string $email = null,
        string $org = null,
        string $org_unit = null,
        string $country = null,
        string $state = null,
        string $city = null
    ): \stdClass {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($email)) {
            $options['email'] = $email;
        }
        if (isset($org)) {
            $options['organization'] = $org;
        }
        if (isset($org_unit)) {
            $options['organization_unit'] = $org_unit;
        }
        if (isset($country)) {
            $options['country'] = $country;
        }
        if (isset($state)) {
            $options['state'] = $state;
        }
        if (isset($city)) {
            $options['city'] = $city;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/csr', $options);
        return $this->body;
    }

    public function modifyDataStorageRegion(int $site_id, string $region): bool
    {
        $options = [
            'site_id' => $site_id,
            'data_storage_region' => $region
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/region-change', $options);
        return empty((array) $this->body);
    }

    public function getDataStorageRegion(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/show', $options);
        return $this->body->region;
    }

    public function enableDataStorageRegionByGeo(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id,
            'override_site_regions_by_geo' => true
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/override-by-geo', $options);
        return (
            isset($this->body->override_site_regions_by_geo) && 
            $this->body->override_site_regions_by_geo == true
        );
    }

    public function disableDataStorageRegionByGeo(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id,
            'override_site_regions_by_geo' => false
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/override-by-geo', $options);
        return (
            isset($this->body->override_site_regions_by_geo) && 
            $this->body->override_site_regions_by_geo == false
        );
    }

    public function isDataStorageRegionByGeoEnabled(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/show-override-by-geo', $options);
        return $this->body->override_site_regions_by_geo;
    }

    public function checkCompliance(int $site_id): array
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/caa/check-compliance', $options);
        return $this->body->non_compliant_sans;
    }

    public function moveSite(int $site_id, int $account_id): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'destination_account_id' => $account_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/moveSite', $options);
        return $this->body;
    }

    public function getDomainApproverEmails(int $site_id)
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/domain/emails', $options);
        return $this->body->domain_emails;
    }

    public function modifyConfig(int $site_id, string $param, $value)
    {
        $options = [
            'site_id' => $site_id,
            'param' => $param,
            'value' => $value
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/configure', $options);
        return $this->body;
    }

    public function modifySecurityConfig(int $site_id, string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/configure/security', $options);
        return $this->body;
    }

    // @todo Error from Incapsula "Invalid input"
    public function modifyACLConfig(int $site_id, string $rule_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id
        ]);

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/configure/acl', $options);
        return $this->body;
    }

    public function modifyWhitelistConfig(
        int $site_id,
        string $rule_id,
        int $whitelist_id = null,
        array $options
    ) {
        $options = array_merge($options, [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
        ]);

        if (isset($whitelist_id)) {
            $options['whitelist_id'] = $whitelist_id;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/configure/whitelists', $options);
        return $this->body;
    }

    public function createWhitelistConfig(
        int $site_id,
        string $rule_id,
        array $options = []
    )
    {
        $this->modifyWhitelistConfig($site_id, $rule_id, null, $options);
    }

    public function deleteWhitelistConfig(int $site_id, string $rule_id, int $whitelist_id)
    {
        $this->modifyWhitelistConfig($site_id, $rule_id, null, [
            'whitelist_id' => $whitelist_id,
            'delete_whitelist' => true
        ]);
    }

    public function getRewritePorts(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/rewrite-port', $options);
        return $this->body;
    }

    public function modifyRewritePorts(
        int $site_id,
        bool $enabled = null,
        int $port = null,
        bool $ssl_enabled = null,
        int $ssl_port = null
    ): bool {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($enabled)) {
            $options['rewrite_port_enabled'] = $enabled;
        }
        if (isset($port)) {
            $options['port'] = $port;
        }
        if (isset($ssl_enabled)) {
            $options['rewrite_ssl_port_enabled'] = $ssl_enabled;
        }
        if (isset($ssl_port)) {
            $options['ssl_port'] = $ssl_port;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/rewrite-port/modify', $options);
        return empty((array) $this->body);
    }

    public function getErrorPage(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/error-page', $options);
        return $this->body->error_page_template;
    }

    // @todo Error from Incapsula "Invalid input"
    public function modifyErrorPage(int $site_id, string $template): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'error_page_template' => $template
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/sites/performance/error-page/modify', $options);
        return $this->body;
    }
}