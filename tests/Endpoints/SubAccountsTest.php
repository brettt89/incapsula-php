<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestConfigure;
use Incapsula\API\Tests\Interfaces\TestEndpoint;

class SubAccountTest extends \TestCase implements TestConfigure, TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\SubAccounts($this->getAdapter());
        }
        return $this->endpoint;
    }

    public function getConfig(): \Incapsula\API\Interfaces\Configuration
    {
        if (!isset($this->config)) {
            $this->config = new \Incapsula\API\Configurations\Account();
        }
        return $this->config;
    }

    public function testGet()
    {
        $this->setAdapter('Endpoints/Account/getSubAccounts.json', '/api/prov/v1/accounts/listSubAccounts');
        $subAccounts = $this->getEndpoint()->getList();

        $this->assertIsArray($subAccounts);
        $this->assertCount(2, $subAccounts);

        $this->assertObjectHasAttribute('sub_account_id', $subAccounts[0]);
        $this->assertEquals(123456, $subAccounts[0]->sub_account_id);
        $this->assertObjectHasAttribute('sub_account_name', $subAccounts[1]);
        $this->assertEquals('My super lovely sub account', $subAccounts[1]->sub_account_name);
    }

    public function testAdd()
    {
        $this->setAdapter(
            'Endpoints/Account/addSubAccount.json',
            '/api/prov/v1/subaccounts/add',
            [
                'sub_account_name' => 'Sub Account Name'
            ]
        );
        
        $subAccount = $this->getEndpoint()->add('Sub Account Name', $this->getConfig());

        $this->assertIsObject($subAccount);

        $this->assertObjectHasAttribute('sub_account_id', $subAccount);
        $this->assertEquals(123456, $subAccount->sub_account_id);
        $this->assertObjectHasAttribute('support_level', $subAccount);
        $this->assertEquals('Standard', $subAccount->support_level);
    }

    public function testDelete()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/subaccounts/delete',
            [
                'sub_account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->delete(12345);

        $this->assertTrue($result);
    }
}
