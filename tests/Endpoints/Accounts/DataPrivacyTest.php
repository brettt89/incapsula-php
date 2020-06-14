<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class DataPrivacyTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Accounts\DataPrivacy($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }

    public function testGetDefault()
    {
        $this->setAdapter(
            'Endpoints/Account/getDataStorageRegion.json',
            '/api/prov/v1/accounts/data-privacy/show',
            [
                'account_id' => 12345
            ]
        );

        $region = $this->getEndpoint()->getDefault();

        $this->assertEquals('EU', $region);
    }

    public function testSetDefault()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/accounts/data-privacy/set-region-default',
            [
                'account_id' => 12345,
                'region' => 'EU'
            ]
        );

        $result = $this->getEndpoint()->setDefault('EU');

        $this->assertTrue($result);
    }
}
