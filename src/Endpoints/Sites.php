<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Endpoints\Endpoint;

class Sites extends Endpoint
{
    public function getStatus(int $site_id, string $tests = null)
    {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($tests)) {
            $options['tests'] = $tests;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/status', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listSites(int $account_id = null, array $pagination_options = []): array
    {
        $options = $account_id !== null ? [
            'account_id' => $account_id
        ] : [];

        $sites = $this->getAdapter()->request('api/prov/v1/sites/list', $options, $pagination_options);

        $this->body = json_decode($sites->getBody());
        return $this->body->sites;
    }

    public function addSite(int $account_id, array $options): \stdClass
    {
        $options = array_merge($options, [
            'account_id' => $account_id
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteSite(int $site_id): bool
    {
        $query = $this->getAdapter()->request('/api/prov/v1/sites/delete', ['site_id' => $site_id]);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function setLogLevel(int $site_id, string $level): bool
    {
        $options = [
            'site_id' => $site_id,
            'log_level' => $level
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/setlog', $options);

        $this->body = json_decode($query->getBody());
        return ($this->body->debug_info->log_level == $level);
    }

    public function setSupportTLS(int $site_id, bool $support): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'support_all_tls_versions' => $support
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/tls', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->debug_info;
    }

    public function getReport(
        int $site_id,
        string $report,
        string $format,
        string $time_range,
        array $time_options = []
    ): \stdClass {
        $options = [
            'site_id' => $site_id,
            'report' => $report,
            'format' => $format,
            'time_range' => $time_range
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/report', $options, $time_options);

        $this->body = json_decode($query->getBody());
        return $this->body->debug_info;
    }

    public function purgeCache(int $site_id, string $pattern = null, string $tag_names = null): bool
    {
        $options = [
            'site_id' => $site_id
        ];

        if (isset($pattern)) {
            $options['pattern'] = $pattern;
        }
        if (isset($tag_names)) {
            $options['tag_names'] = $tag_names;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/cache/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getGeeTest(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/geetest-level', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setGeeTest(int $site_id, string $algorithm): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'challenge_algorithm' => $algorithm
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/geetest-level/modify', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function purgeHostnameCache(int $site_id, string $host_name): bool
    {
        $options = [
            'site_id' => $site_id,
            'host_name' => $host_name
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/hostname/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function uploadCustomCertificate(
        int $site_id,
        string $crt_base64,
        string $key_base64 = null,
        string $passphrase = null
    ): \stdClass {
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/upload', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function removeCustomCertificate(int $site_id, string $host_name): bool
    {
        $options = [
            'site_id' => $site_id,
            'host_name' => $host_name
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/remove', $options);

        $this->body = json_decode($query->getBody());
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
    ): string {
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/customCertificate/csr', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->csr_content;
    }

    public function addIncapRule(
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

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/add', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function updateIncapRule(
        int $rule_id,
        string $action,
        array $rule_options = []
    ): \stdClass {
        $options = array_merge($rule_options, [
            'rule_id' => $rule_id,
            'action' => $action
        ]);

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/edit', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function enableDisableIncapRule(int $rule_id, bool $enable): \stdClass
    {
        $options = [
            'rule_id' => $rule_id,
            'enabled' => $enable
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/enableDisable', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function deleteIncapRule(int $rule_id): \stdClass
    {
        $options = [
            'rule_id' => $rule_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/delete', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listIncapRules(
        int $site_id,
        bool $inc_delivery = null,
        bool $inc_incap = null,
        array $pagination_options = []
    ): \stdClass {
        $options = array_merge($pagination_options, [
            'site_id' => $site_id
        ]);

        if (isset($inc_delivery)) {
            $options['include_ad_rules'] = $inc_delivery;
        }
        if (isset($inc_incap)) {
            $options['include_incap_rules'] = $inc_incap;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function listAccountIncapRules(
        int $site_id,
        bool $inc_delivery = null,
        bool $inc_incap = null,
        array $page_options = []
    ): \stdClass {
        $options = array_merge($page_options, [
            'site_id' => $site_id
        ]);

        if (isset($inc_delivery)) {
            $options['include_ad_rules'] = $inc_delivery;
        }
        if (isset($inc_incap)) {
            $options['include_incap_rules'] = $inc_incap;
        }

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/account/list', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setIncapRulePriority(int $site_id, int $rule_id, int $priority): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'rule_id' => $rule_id,
            'priority' => $priority
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/incapRules/priority/set', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function getXRayLink(int $site_id): \stdClass
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/xray/get-link', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function setDataStorageRegion(int $site_id, string $region): bool
    {
        $options = [
            'site_id' => $site_id,
            'data_storage_region' => $region
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/region-change', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function getDataStorageRegion(int $site_id): string
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/show', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->region;
    }

    public function setDataStorageRegionByGeo(int $account_id, bool $value): bool
    {
        $options = [
            'account_id' => $account_id,
            'override_site_regions_by_geo' => $value
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/override-by-geo', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }

    public function isDataStorageRegionByGeoEnabled(int $account_id): bool
    {
        $options = [
            'account_id' => $account_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/data-privacy/show-override-by-geo', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->override_site_regions_by_geo;
    }

    public function checkCompliance(int $site_id): array
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/caa/check-compliance', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->non_compliant_sans;
    }

    public function moveSite(int $site_id, int $account_id): \stdClass
    {
        $options = [
            'site_id' => $site_id,
            'destination_account_id' => $account_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/moveSite', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }
}
