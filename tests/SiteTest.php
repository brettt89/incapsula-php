<?php

namespace Incapsula\API\Tests;

use Incapsula\API\Tests\TestAPI;

class SiteTest extends \TestCase implements TestAPI
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\API
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Site($this->getAdapter());
        }
        return $this->endpoint;
    }
    
    public function testGetStatus()
    {
        $this->setAdapter(
            'Site/getStatus.json',
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

    public function testGetDomainApproverEmails()
    {
        $this->setAdapter(
            'Domain/getEmails.json',
            '/api/prov/v1/domain/emails',
            [
                'site_id' => 12345,
            ]
        );
        $emails = $this->getEndpoint()->getDomainApproverEmails(12345);

        $this->assertIsArray($emails);
        $this->assertCount(2, $emails);

        $this->assertEquals('admin@example.com', $emails[0]);
        $this->assertEquals('webmaster@example.com', $emails[1]);
    }

    //
    // Managed Sites
    //

    public function testListSites()
    {
        $this->setAdapter(
            'Site/getSites.json',
            'api/prov/v1/sites/list',
            [
                'account_id' => 12345
            ]
        );
        $sites = $this->getEndpoint()->listSites(12345);

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
            'Site/getStatus.json',
            '/api/prov/v1/sites/add',
            [
                'account_id' => 12345,
                'domain' => 'www.example.com',
                'site_ip' => '1.2.3.4'
            ]
        );

        $site = $this->getEndpoint()->addSite('www.example.com', [
            'account_id' => 12345,
            'site_ip' => '1.2.3.4'
            ]);

        $this->assertIsObject($site);

        $this->assertObjectHasAttribute('status', $site);
        $this->assertEquals('pending-dns-changes', $site->status);
        $this->assertObjectHasAttribute('ips', $site);
        $this->assertEquals('34.33.22.1', $site->ips[0]);
    }

    public function testDeleteSite()
    {
        $this->setAdapter(
            'success.json',
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

    public function testSetConfig()
    {
        $this->setAdapter(
            'Site/setConfig.json',
            '/api/prov/v1/sites/configure',
            [
                'site_id' => 12345,
                'param' => 'domain_email',
                'value' => 'admin@example.com'
            ]
        );

        $result = $this->getEndpoint()->setConfig(12345, 'domain_email', 'admin@example.com');

        $this->assertIsObject($result);
    }

    public function testSetSecurityConfig()
    {
        $this->setAdapter(
            'Site/getStatus.json',
            '/api/prov/v1/sites/configure/security',
            [
                'site_id' => 12345,
                'rule_id' => 'api.threats.bot_access_control',
                'block_bad_bots' => true,
                'challenge_suspected_bots' => true
            ]
        );

        $result = $this->getEndpoint()->setSecurityConfig(
            12345,
            'api.threats.bot_access_control',
            [
                'block_bad_bots' => true,
                'challenge_suspected_bots' => true
            ]
        );

        $this->assertIsObject($result);
    }

    public function testSetACLConfig()
    {
        $this->setAdapter(
            'Site/getStatus.json',
            '/api/prov/v1/sites/configure/acl',
            [
                'site_id' => 12345,
                'rule_id' => 'api.acl.blacklisted_urls',
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'url_patterns' => 'contains,equals'
            ]
        );

        $result = $this->getEndpoint()->setACLConfig(
            12345,
            'api.acl.blacklisted_urls',
            [
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'url_patterns' => 'contains,equals'
            ]
        );

        $this->assertIsObject($result);
    }

    public function testSetWhitelistConfig()
    {
        $this->setAdapter(
            'Site/getStatus.json',
            '/api/prov/v1/sites/configure/whitelists',
            [
                'site_id' => 12345,
                'rule_id' => 'api.threats.sql_injection',
                'whitelist_id' => 12345,
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'ips' => '1.2.3.4,192.168.1.1-192.168.1.100,192.168.1.1/24',
                'countries' => 'GT,VN'
            ]
        );

        $result = $this->getEndpoint()->setWhitelistConfig(
            12345,
            'api.threats.sql_injection',
            12345,
            [
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'ips' => '1.2.3.4,192.168.1.1-192.168.1.100,192.168.1.1/24',
                'countries' => 'GT,VN'
            ]
        );

        $this->assertIsObject($result);
    }

    public function testSetLogLevel()
    {
        $this->setAdapter(
            'Site/setLogLevel.json',
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
            'Site/setSupportTLS.json',
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

    public function testGetReport()
    {
        $this->setAdapter(
            'Site/getReport.json',
            '/api/prov/v1/sites/report',
            [
                'site_id' => 12345,
                'report' => 'pci-compliance',
                'format' => 'pdf',
                'time_range' => 'custom',
                'start' => '0123456789',
                'end' => '9876543210'
            ]
        );

        $result = $this->getEndpoint()->getReport(
            12345,
            'pci-compliance',
            'pdf',
            'custom',
            [
                'start' => '0123456789',
                'end' => '9876543210'
            ]
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('report', $result);
        $this->assertEquals(
            'JVBERi0xLjUNCiXvv73vv73vv73vv70NCjEgMCBvYmoNCjw8L1R5cGUvQ2F0YWxvZy9QYWdlcyAyIDAgUi9MYW5nKGVuLVVT ...',
            $result->report
        );
    }

    public function testGetGeeTest()
    {
        $this->setAdapter(
            'Site/getGeeTest.json',
            '/api/prov/v1/sites/geetest-level',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getGeeTest(12345);

        $this->assertIsString($result);
        $this->assertEquals('reCAPTCHA 2.0', $result);
    }

    public function testSetGeeTest()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/geetest-level/modify',
            [
                'site_id' => 12345,
                'challenge_algorithm' => 'test algorithm'
            ]
        );

        $result = $this->getEndpoint()->setGeeTest(12345, 'test algorithm');

        $this->assertIsObject($result);
    }

    public function testUploadCustomCertificate()
    {
        $this->setAdapter(
            'success.json',
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

        $this->assertTrue($result);
    }

    public function testRemoveCustomCertificate()
    {
        $this->setAdapter(
            'success.json',
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
            'Site/getCSR.json',
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

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('csr_content', $result);
        $this->assertEquals(
            "-----BEGIN CERTIFICATE REQUEST-----\nMIICyTCCAbECAQAwgYMxCzAJBgNVBAYTAlVTMREwDwYDVQQIEwhEZWxhd2FyZTEO...",
            $result->csr_content
        );
    }

    public function testSetDataStorageRegion()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/data-privacy/region-change',
            [
                'site_id' => 12345,
                'data_storage_region' => 'APAC'
            ]
        );

        $result = $this->getEndpoint()->setDataStorageRegion(12345, 'APAC');

        $this->assertTrue($result);
    }

    public function testGetDataStorageRegion()
    {
        $this->setAdapter(
            'Account/getDataStorageRegion.json',
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
            'Site/setDataStorageRegionByGeo.json',
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
            'Site/isDataStorageRegionByGeoEnabled.json',
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
            'Site/checkCompliance.json',
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
            'Site/moveSite.json',
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

    public function testGetRewritePort()
    {
        $this->setAdapter(
            'Site/Performance/getRewritePort.json',
            '/api/prov/v1/sites/performance/rewrite-port',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getRewritePort(12345);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('port', $result);
        $this->assertIsObject($result->port);
        $this->assertEquals('80', $result->port->from);
        $this->assertEquals('8080', $result->port->to);
    }

    public function testSetRewritePort()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/rewrite-port/modify',
            [
                'site_id' => 12345,
                'rewrite_port_enabled' => true,
                'port' => 8080,
                'rewrite_ssl_port_enabled' => true,
                'ssl_port' => 444
            ]
        );

        $result = $this->getEndpoint()->setRewritePort(12345, true, 8080, true, 444);

        $this->assertTrue($result);
    }

    public function testGetErrorPage()
    {
        $this->setAdapter(
            'Site/getErrorPage.json',
            '/api/prov/v1/sites/performance/error-page',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->getErrorPage(12345);

        $this->assertIsString($result);
        $this->assertEquals('Test Page', $result);
    }

    public function testSetErrorPage()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/sites/performance/error-page/modify',
            [
                'site_id' => 12345,
                'error_page_template' => '<html><body></body></html>'
            ]
        );

        $result = $this->getEndpoint()->setErrorPage(12345, '<html><body></body></html>');

        $this->assertIsObject($result);
    }
}
