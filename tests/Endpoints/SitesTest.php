<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestConfigure;
use Incapsula\API\Tests\Interfaces\TestEndpoint;

class SitesTest extends \TestCase implements TestConfigure, TestEndpoint
{
    private $endpoint;
    private $config;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites($this->getAdapter());
        }
        return $this->endpoint;
    }

    public function getConfig(): \Incapsula\API\Interfaces\Configuration
    {
        if (!isset($this->config)) {
            $this->config = new \Incapsula\API\Configurations\Site('www.example.com');
        }
        return $this->config;
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
    // Secure Resource
    //

    //
    // Stale Content
    //

    //
    // Managed Sites
    //

    public function testGetList()
    {
        $this->setAdapter('Endpoints/Site/getSites.json', 'api/prov/v1/sites/list');
        $sites = $this->getEndpoint()->getList();

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

    public function testAdd()
    {
        $this->setAdapter(
            'Endpoints/Site/getStatus.json',
            '/api/prov/v1/sites/add',
            [
                'account_id' => 12345,
                'domain' => 'www.example.com'
            ]
        );

        $site = $this->getEndpoint()->add(12345, $this->getConfig());

        $this->assertIsObject($site);

        $this->assertObjectHasAttribute('status', $site);
        $this->assertEquals('pending-dns-changes', $site->status);
        $this->assertObjectHasAttribute('ips', $site);
        $this->assertEquals('34.33.22.1', $site->ips[0]);
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

    public function testDelete()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/delete',
            [
                'site_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->delete(12345);

        $this->assertTrue($result);
    }
}
