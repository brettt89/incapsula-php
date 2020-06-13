<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestConfigure;
use Incapsula\API\Tests\Interfaces\TestEndpoint;

class ConfigureTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Accounts\Configure($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }

    public function testSet()
    {
        $this->setAdapter(
            'Endpoints/Account/setConfiguration.json',
            '/api/prov/v1/accounts/configure',
            [
                'account_id' => 12345,
                'param' => 'email',
                'value' => 'admin@example.com'
            ]
        );

        $result = $this->getEndpoint()->set('email', 'admin@example.com');

        $this->assertTrue($result);
    }
}
