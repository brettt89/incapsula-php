<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class ConfigureTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Configure($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testSet()
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

        $result = $this->getEndpoint()->set('domain_email', 'admin@example.com');

        $this->assertTrue($result);
    }

    public function testSetSecurity()
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

        $result = $this->getEndpoint()->setSecurity(
            'api.threats.bot_access_control',
            [
                'block_bad_bots' => true,
                'challenge_suspected_bots' => true
            ]
        );

        $this->assertIsObject($result);
    }

    public function testSetACL()
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

        $result = $this->getEndpoint()->setACL(
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
}