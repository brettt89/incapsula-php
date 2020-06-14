<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestConfigure;
use Incapsula\API\Tests\Interfaces\TestEndpoint;

class SitesTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites($this->getAdapter());
        }
        return $this->endpoint;
    }
    
    public function testGetStatus()
    {
        $this->setAdapter(
            'Endpoints/Site/getStatus.json',
            '/api/prov/v1/sites/status',
            [
                'site_id' => 12345,
                'tests' => 'domain_validation, services, dns'
            ]
        );
        $status = $this->getEndpoint()->getStatus(12345, 'domain_validation, services, dns');

        $this->assertIsObject($status);

        $this->assertObjectHasAttribute('status', $status);
        $this->assertEquals('pending-dns-changes', $status->status);
        $this->assertObjectHasAttribute('ips', $status);
        $this->assertEquals('34.33.22.1', $status->ips[0]);
    }

    //
    // Managed Sites
    //

    public function testListSites()
    {
        $this->setAdapter('Endpoints/Site/getSites.json', 'api/prov/v1/sites/list');
        $sites = $this->getEndpoint()->listSites();

        $this->assertIsArray($sites);
        $this->assertCount(2, $sites);

        $this->assertObjectHasAttribute('status', $sites[0]);
        $this->assertEquals('Test-Status', $sites[0]->status);
        $this->assertObjectHasAttribute('status', $sites[1]);
        $this->assertEquals('Test-Status 2', $sites[1]->status);

        $this->assertObjectHasAttribute('ips', $sites[0]);
        $this->assertIsArray($sites[0]->ips);
        $this->assertEquals('34.33.22.1', $sites[0]->ips[0]);

        $this->assertObjectHasAttribute('ips', $sites[1]);
        $this->assertIsArray($sites[1]->ips);
        $this->assertEquals('11.22.33.44', $sites[1]->ips[0]);
    }

    public function testAddSite()
    {
        $this->setAdapter(
            'Endpoints/Site/getStatus.json',
            '/api/prov/v1/sites/add',
            [
                'account_id' => 12345,
                'domain' => 'www.example.com'
            ]
        );

        $site = $this->getEndpoint()->addSite(12345, ['domain' => 'www.example.com']);

        $this->assertIsObject($site);

        $this->assertObjectHasAttribute('status', $site);
        $this->assertEquals('pending-dns-changes', $site->status);
        $this->assertObjectHasAttribute('ips', $site);
        $this->assertEquals('34.33.22.1', $site->ips[0]);
    }

    public function testDeleteSite()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/delete',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->deleteSite(12345);

        $this->assertTrue($result);
    }

    //
    // Site Configuration
    //

    public function testSetLogLevel()
    {
        $this->setAdapter(
            'Endpoints/Site/setLogLevel.json',
            '/api/prov/v1/sites/setlog',
            [
                'site_id' => 12345,
                'log_level' => 'full'
            ]
        );

        $result = $this->getEndpoint()->setLogLevel(12345, 'full');

        $this->assertTrue($result);
    }

    public function testSetSupportTLS()
    {
        $this->setAdapter(
            'Endpoints/Site/setSupportTLS.json',
            '/api/prov/v1/sites/tls',
            [
                'site_id' => 12345,
                'support_all_tls_versions' => true
            ]
        );

        $result = $this->getEndpoint()->setSupportTLS(12345, true);

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('support_all_tls_versions', $result);
        $this->assertEquals('true', $result->support_all_tls_versions);
        $this->assertObjectHasAttribute('new_A_record', $result);
        $this->assertEquals('1.2.3.4', $result->new_A_record);
    }

    public function testPurgeCache()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/cache/purge',
            [
                'site_id' => 12345,
                'pattern' => 'testPattern',
                'tag_names' => 'test tag,tag2'
            ]
        );

        $result = $this->getEndpoint()->purgeCache(12345, 'testPattern', 'test tag,tag2');

        $this->assertTrue($result);
    }

    public function testGetGeeTest()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/geetest-level',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getGeeTest(12345);

        $this->assertIsObject($result);
    }

    public function testSetGeeTest()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/geetest-level/modify',
            [
                'site_id' => 12345,
                'challenge_algorithm' => 'test algorithm'
            ]
        );

        $result = $this->getEndpoint()->setGeeTest(12345, 'test algorithm');

        $this->assertIsObject($result);
    }

    public function testPurgeHostnameCache()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/hostname/purge',
            [
                'site_id' => 12345,
                'host_name' => 'www.example.com'
            ]
        );

        $result = $this->getEndpoint()->purgeHostnameCache(12345, 'www.example.com');

        $this->assertTrue($result);
    }

    public function testUploadCustomCertificate()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/customCertificate/upload',
            [
                'site_id' => 12345,
                'certificate' => base64_encode('Test Certificate'),
                'private_key' => base64_encode('Test Key'),
                'passphrase' => 'testPassphrase'
            ]
        );

        $result = $this->getEndpoint()->uploadCustomCertificate(
            12345,
            base64_encode('Test Certificate'),
            base64_encode('Test Key'),
            'testPassphrase'
        );

        $this->assertIsObject($result);
    }

    public function testRemoveCustomCertificate()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/customCertificate/remove',
            [
                'site_id' => 12345,
                'host_name' => 'www.example.com'
            ]
        );

        $result = $this->getEndpoint()->removeCustomCertificate(12345, 'www.example.com');

        $this->assertTrue($result);
    }

    public function testCreateCSR()
    {
        $this->setAdapter(
            'Endpoints/Site/getCSR.json',
            '/api/prov/v1/sites/customCertificate/csr',
            [
                'site_id' => 12345,
                'email' => 'admin@example.com',
                'organization' => 'test org',
                'organization_unit' => 'test unit',
                'country' => 'US',
                'state' => 'New York',
                'city' => 'New York City'
            ]
        );

        $result = $this->getEndpoint()->createCSR(
            12345,
            'admin@example.com',
            'test org',
            'test unit',
            'US',
            'New York',
            'New York City'
        );

        $this->assertIsString($result);
        $this->assertEquals(
            "-----BEGIN CERTIFICATE REQUEST-----\nMIICyTCCAbECAQAwgYMxCzAJBgNVBAYTAlVTMREwDwYDVQQIEwhEZWxhd2FyZTEO...",
            $result
        );
    }

    public function testAddIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/add',
            [
                'site_id' => 12345,
                'name' => 'test name',
                'action' => 'RULE_ACTION_REDIRECT',
                'filter' => 'test Filter',
                'response_code' => 312
            ]
        );

        $result = $this->getEndpoint()->addIncapRule(12345, 'test name', 'RULE_ACTION_REDIRECT', [
            'filter' => 'test Filter',
            'response_code' => 312
        ]);

        $this->assertIsObject($result);
    }

    public function testUpdateIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/edit',
            [
                'rule_id' => 98765,
                'action' => 'RULE_ACTION_REDIRECT',
                'filter' => 'test Filter',
                'response_code' => 312
            ]
        );

        $result = $this->getEndpoint()->updateIncapRule(98765, 'RULE_ACTION_REDIRECT', [
            'filter' => 'test Filter',
            'response_code' => 312
        ]);

        $this->assertIsObject($result);
    }

    public function testEnableDisableIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/enableDisable',
            [
                'rule_id' => 98765,
                'enabled' => false
            ]
        );

        $result = $this->getEndpoint()->enableDisableIncapRule(98765, false);

        $this->assertIsObject($result);
    }

    public function testDelteIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/delete',
            [
                'rule_id' => 98765
            ]
        );

        $result = $this->getEndpoint()->deleteIncapRule(98765);

        $this->assertIsObject($result);
    }

    public function testListAccountIncapRules()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/account/list',
            [
                'site_id' => 12345,
                'include_ad_rules' => true,
                'include_incap_rules' => false,
                'page_size' => 100,
                'page_num' => 2
            ]
        );

        $result = $this->getEndpoint()->listAccountIncapRules(12345, true, false, [
            'page_size' => 100,
            'page_num' => 2
        ]);

        $this->assertIsObject($result);
    }

    public function testSetIncapRulePriority()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/priority/set',
            [
                'site_id' => 12345,
                'rule_id' => 98765,
                'priority' => 10
            ]
        );

        $result = $this->getEndpoint()->setIncapRulePriority(12345, 98765, 10);

        $this->assertIsObject($result);
    }

    public function testGetXRayLink()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/xray/get-link',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getXRayLink(12345);

        $this->assertIsObject($result);
    }

    public function testSetDataStorageRegion()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/data-privacy/region-change',
            [
                'site_id' => 12345,
                'data_storage_region' => 'AP'
            ]
        );

        $result = $this->getEndpoint()->setDataStorageRegion(12345, 'AP');

        $this->assertTrue($result);
    }

    public function testGetDataStorageRegion()
    {
        $this->setAdapter(
            'Endpoints/Account/getDataStorageRegion.json',
            '/api/prov/v1/sites/data-privacy/show',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getDataStorageRegion(12345);

        $this->assertIsString($result);
        $this->assertEquals('EU', $result);
    }

    public function testSetDataStorageRegionByGeo()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/data-privacy/override-by-geo',
            [
                'account_id' => 12345,
                'override_site_regions_by_geo' => true
            ]
        );

        $result = $this->getEndpoint()->setDataStorageRegionByGeo(12345, true);

        $this->assertTrue($result);
    }

    public function testIsDataStorageRegionByGeoEnabled()
    {
        $this->setAdapter(
            'Endpoints/Site/isDataStorageRegionByGeoEnabled.json',
            '/api/prov/v1/sites/data-privacy/show-override-by-geo',
            [
                'account_id' => 12345,
            ]
        );

        $result = $this->getEndpoint()->isDataStorageRegionByGeoEnabled(12345);

        $this->assertTrue($result);
    }

    public function testCheckCompliance()
    {
        $this->setAdapter(
            'Endpoints/Site/checkCompliance.json',
            '/api/prov/v1/caa/check-compliance',
            [
                'site_id' => 12345,
            ]
        );

        $result = $this->getEndpoint()->checkCompliance(12345);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('*.caa.incaptest.co', $result[0]);
    }

    public function testMoveSite()
    {
        $this->setAdapter(
            'Endpoints/Site/moveSite.json',
            '/api/prov/v1/sites/moveSite',
            [
                'site_id' => 12345,
                'destination_account_id' => 54321
            ]
        );

        $result = $this->getEndpoint()->moveSite(12345, 54321);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertEquals('', $result->status);
    }
}
