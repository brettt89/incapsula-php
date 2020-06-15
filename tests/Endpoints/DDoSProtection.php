<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class DDoSProtectionTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\DDoSProtection($this->getAdapter());
        }
        return $this->endpoint;
    }

    public function testAddOriginIP()
    {
        $this->setAdapter(
            'Endpoints/DDoSProtection/addOriginIP.json',
            '/api/prov/v1/ddos-protection/edge-ip/add/ip',
            [
                'origin_ip' => '1.2.3.4',
                'enable_ha_protocol' => false,
                'description' => 'Test Origin'
            ]
        );

        $result = $this->getEndpoint()->addOriginIP(
            '1.2.3.4',
            false,
            'Test Origin'
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('origin_ip', $result);
        $this->assertEquals('1.2.3.4', $result->origin_ip);
        $this->assertObjectHasAttribute('edge_ip', $result);
        $this->assertEquals('172.17.14.1', $result->edge_ip);
    }

    public function testAddOriginCNAME()
    {
        $this->setAdapter(
            'Endpoints/DDoSProtection/addOriginCNAME.json',
            '/api/prov/v1/ddos-protection/edge-ip/add/cname',
            [
                'cname' => 'imperva.test.com',
                'enable_ha_protocol' => false,
                'description' => 'Test Origin CNAME'
            ]
        );

        $result = $this->getEndpoint()->addOriginCNAME(
            'imperva.test.com',
            false,
            'Test Origin CNAME'
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('cname', $result);
        $this->assertEquals('imperva.test.com', $result->cname);
        $this->assertObjectHasAttribute('edge_ip', $result);
        $this->assertEquals('172.17.14.1', $result->edge_ip);
    }

    public function testAddOriginDNSIP()
    {
        $this->setAdapter(
            'Endpoints/DDoSProtection/addOriginDNSIP.json',
            '/api/prov/v1/ddos-protection/edge-ip/add/dns-with-ip',
            [
                'dns_name' => 'www.example.com',
                'origin_ip' => '1.2.3.4',
                'disable_dns_check' => false,
                'enable_ha_protocol' => false,
                'description' => 'Test Origin'
            ]
        );

        $result = $this->getEndpoint()->addOriginDNSIP(
            'www.example.com',
            '1.2.3.4',
            false,
            false,
            'Test Origin'
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('origin_ip', $result);
        $this->assertEquals('157.166.249.10', $result->origin_ip);
        $this->assertObjectHasAttribute('edge_ip', $result);
        $this->assertEquals('172.17.14.1', $result->edge_ip);

        $this->assertObjectHasAttribute('resolved_ips', $result);
        $this->assertIsArray($result->resolved_ips);
        $this->assertEquals('157.166.226.25', $result->resolved_ips[0]);
    }

    public function testAddOriginDNSCNAME()
    {
        $this->setAdapter(
            'Endpoints/DDoSProtection/addOriginDNSCNAME.json',
            '/api/prov/v1/ddos-protection/edge-ip/add/dns-with-cname',
            [
                'dns_name' => 'www.example.com',
                'cname' => 'imperva.test.com',
                'disable_dns_check' => false,
                'enable_ha_protocol' => false,
                'description' => 'Test Origin CNAME'
            ]
        );

        $result = $this->getEndpoint()->addOriginDNSCNAME(
            'www.example.com',
            'imperva.test.com',
            false,
            false,
            'Test Origin CNAME'
        );

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('cname', $result);
        $this->assertEquals('imperva.test.com', $result->cname);
        $this->assertObjectHasAttribute('edge_ip', $result);
        $this->assertEquals('172.17.14.1', $result->edge_ip);

        $this->assertObjectHasAttribute('resolved_cnames', $result);
        $this->assertIsArray($result->resolved_cnames);
        $this->assertEquals('imperva.test.com', $result->resolved_cnames[0]);
    }

    public function testEnableHAProtocol()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/ddos-protection/edge-ip/edit/ha-protocol',
            [
                'edge_ip' => '1.2.3.4',
                'enable_ha_protocol' => false
            ]
        );

        $result = $this->getEndpoint()->enableHAProtocol('1.2.3.4', false);

        $this->assertTrue($result);
    }

    public function testDeleteEdgeIP()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/ddos-protection/edge-ip/remove',
            [
                'edge_ip' => '1.2.3.4'
            ]
        );

        $result = $this->getEndpoint()->deleteEdgeIP('1.2.3.4');

        $this->assertTrue($result);
    }
}
