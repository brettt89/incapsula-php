<?php

namespace Incapsula\API\Tests\Endpoints;

use GuzzleHttp\Psr7\Response;

class SiteTest extends \TestCase
{
    private $siteConfig;
    private $endpoint;

    protected function getEndpoint()
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Site($this->getAdapter());
        }
        return $this->endpoint;
    }

    protected function getSiteConfig()
    {
        if (!isset($this->siteConfig)) {
            $this->siteConfig = new \Incapsula\API\Configurations\Site('www.example.com');
        }
        return $this->siteConfig;
    }

    //
    // Site Controls
    //

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

    public function testGetEmails()
    {
        $this->setAdapter(
            'Endpoints/Site/getEmails.json',
            '/api/prov/v1/domain/emails',
            [
                'site_id' => 12345,
            ]
        );
        $emails = $this->getEndpoint()->getEmails(12345);

        $this->assertIsArray($emails);
        $this->assertCount(2, $emails);

        $this->assertEquals('admin@example.com', $emails[0]);
        $this->assertEquals('webmaster@example.com', $emails[1]);
    }

    //
    // Cache
    //

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

    public function testGetCacheMode()
    {
        $this->setAdapter(
            'Endpoints/Site/getCacheMode.json',
            '/api/prov/v1/sites/performance/cache-mode/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getCacheMode(12345);

        $this->assertIsString($mode);
        $this->assertEquals('dynamic_and_aggressive', $mode);
    }

    public function testSetCacheMode()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/cache-mode',
            [
                'site_id' => 12345,
                'cache_mode' => 'static_and_aggressive',
                'dynamic_cache_duration' => '5_min',
                'aggressive_cache_duration' => '1_hr'
            ]
        );

        $result = $this->getEndpoint()->setCacheMode(12345, 'static_and_aggressive', '5_min', '1_hr');

        $this->assertTrue($result);
    }

    //
    // Secure Resource
    //

    public function testGetSecureResourceMode()
    {
        $this->setAdapter(
            'Endpoints/Site/getSecureResourceMode.json',
            '/api/prov/v1/sites/performance/secure-resources/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getSecureResourceMode(12345);

        $this->assertIsString($mode);
        $this->assertEquals('do_not_cache', $mode);
    }

    public function testSetSecureResourceMode()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/secure-resources',
            [
                'site_id' => 12345,
                'secured_resources_mode' => 'do_not_cache'
            ]
        );

        $result = $this->getEndpoint()->setSecureResourceMode(12345, 'do_not_cache');

        $this->assertTrue($result);
    }

    //
    // Stale Content
    //

    public function testGetStaleContentSettings()
    {
        $this->setAdapter(
            'Endpoints/Site/getStaleContentSettings.json',
            '/api/prov/v1/sites/performance/stale-content/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->getStaleContentSettings(12345);

        $this->assertIsObject($mode);
    }

    public function testSetStaleContentSettings()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/stale-content',
            [
                'site_id' => 12345,
                'serve_stale_content' => true,
                'stale_content_mode' => 'ADAPTIVE',
                'time' => 5,
                'time_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->setStaleContentSettings(12345, true, 'ADAPTIVE', 5, 'MINUTES');

        $this->assertTrue($result);
    }

    //
    // Managed Sites
    //

    public function testGetSites()
    {
        $this->setAdapter('Endpoints/Site/getSites.json', 'api/prov/v1/sites/list');
        $sites = $this->getEndpoint()->getSites();

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

        $site = $this->getEndpoint()->addSite(12345, $this->getSiteConfig());

        $this->assertIsObject($site);

        $this->assertObjectHasAttribute('status', $site);
        $this->assertEquals('pending-dns-changes', $site->status);
        $this->assertObjectHasAttribute('ips', $site);
        $this->assertEquals('34.33.22.1', $site->ips[0]);
    }

    //
    // Site Configuration
    //

    public function testSetConfig()
    {
        $this->setAdapter(
            'Endpoints/Site/setConfig.json',
            '/api/prov/v1/sites/configure',
            [
                'site_id' => 12345,
                'param' => 'domain_email',
                'value' => 'admin@example.com'
            ]
        );

        $result = $this->getEndpoint()->setConfig(12345, 'domain_email', 'admin@example.com');

        $this->assertTrue($result);
    }

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

    //
    // Security
    //

    public function testSetSecurityConfig()
    {
        $this->setAdapter(
            'Endpoints/Site/getStatus.json',
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
            'Endpoints/Site/getStatus.json',
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

    public function testSetWhitelist()
    {
        $this->setAdapter(
            'Endpoints/Site/getStatus.json',
            '/api/prov/v1/sites/configure/whitelists',
            [
                'site_id' => 12345,
                'rule_id' => 'api.threats.sql_injection',
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'ips' => '1.2.3.4,192.168.1.1-192.168.1.100,192.168.1.1/24',
                'countries' => 'GT,VN'
            ]
        );

        $result = $this->getEndpoint()->setWhitelist(
            12345,
            'api.threats.sql_injection',
            null,
            [
                'urls' => '%2Fadmin%2Fdashboard%2Fstats%3Fx%3D1%26y%3D2%23z%3D3,%2Fadmin',
                'ips' => '1.2.3.4,192.168.1.1-192.168.1.100,192.168.1.1/24',
                'countries' => 'GT,VN'
            ]
        );

        $this->assertIsObject($result);
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
}
