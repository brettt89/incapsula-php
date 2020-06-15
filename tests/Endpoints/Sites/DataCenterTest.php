<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class DataCenterTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\DataCenter($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testAddDataCenter()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/add',
            [
                'site_id' => 12345,
                'name' => 'testName',
                'server_address' => '1.2.3.4',
                'is_enabled' => true,
                'is_standby' => false,
                'is_content' => true,
                'lb_algorithm' => 'test algorithm'
            ]
        );

        $mode = $this->getEndpoint()->addDataCenter(
            12345,
            'testName',
            '1.2.3.4',
            true,
            false,
            true,
            'test algorithm'
        );

        $this->assertIsObject($mode);
    }

    public function testEditDataCenter()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/edit',
            [
                'dc_id' => 54321,
                'name' => 'testName',
                'is_enabled' => true,
                'is_standby' => false,
                'is_content' => true,
            ]
        );

        $mode = $this->getEndpoint()->editDataCenter(
            54321,
            'testName',
            true,
            false,
            true
        );

        $this->assertIsObject($mode);
    }

    public function testResumeDataCenterTraffic()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/resume',
            [
                'site_id' => 12345,
            ]
        );

        $mode = $this->getEndpoint()->resumeDataCenterTraffic(12345);

        $this->assertIsObject($mode);
    }

    public function testDeleteDataCenter()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/delete',
            [
                'dc_id' => 54321,
            ]
        );

        $mode = $this->getEndpoint()->deleteDataCenter(54321);

        $this->assertIsObject($mode);
    }

    public function testAddServer()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/servers/add',
            [
                'dc_id' => 54321,
                'server_address' => '1.2.3.4',
                'is_standby' => false
            ]
        );

        $mode = $this->getEndpoint()->addServer(54321, '1.2.3.4', false);

        $this->assertIsObject($mode);
    }

    public function testEditServer()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/servers/edit',
            [
                'server_id' => 98765,
                'server_address' => '4.3.2.1',
                'is_enabled' => true,
                'is_standby' => false
            ]
        );

        $mode = $this->getEndpoint()->editServer(98765, '4.3.2.1', true, false);

        $this->assertIsObject($mode);
    }

    public function testDeleteServer()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/servers/delete',
            [
                'server_id' => 54321
            ]
        );

        $mode = $this->getEndpoint()->deleteServer(54321);

        $this->assertIsObject($mode);
    }

    public function testListDataCenters()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/dataCenters/list',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->listDataCenters(12345);

        $this->assertIsObject($mode);
    }

    public function testGDataCenterOriginPOP()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/datacenter/origin-pop/modify',
            [
                'dc_id' => 54321,
                'origin_pop' => 'AP'
            ]
        );

        $mode = $this->getEndpoint()->setDataCenterOriginPOP(54321, 'AP');

        $this->assertIsObject($mode);
    }

    public function testGetDataCenterRecommendedOriginPOP()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/datacenter/origin-pop/recommend',
            [
                'dc_id' => 54321
            ]
        );

        $mode = $this->getEndpoint()->getDataCenterRecommendedOriginPOP(54321);

        $this->assertIsObject($mode);
    }
}
