<?php

namespace Incapsula\API\Tests\Endpoints;

use GuzzleHttp\Psr7\Response;

class AccountTest extends \TestCase
{
    private $accountConfig;
    private $endpoint;

    protected function getEndpoint()
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Account($this->getAdapter());
        }
        return $this->endpoint;
    }

    protected function getAccountConfig()
    {
        if (!isset($this->accountConfig)) {
            $this->accountConfig = new \Incapsula\API\Configurations\Account();
        }
        return $this->accountConfig;
    }

    //
    // Account Controls
    //

    public function testGetStatus()
    {
        $this->setAdapter('Endpoints/Account/getStatus.json', '/api/prov/v1/account');
        $status = $this->getEndpoint()->getStatus();

        $this->assertIsObject($status);

        $this->assertObjectHasAttribute('email', $status);
        $this->assertEquals('demo_account@imperva.com', $status->email);
        $this->assertObjectHasAttribute('plan_name', $status);
        $this->assertEquals('Enterprise', $status->plan_name);
    }

    public function testLoginToken()
    {
        $this->setAdapter('Endpoints/Account/getLoginToken.json', '/api/prov/v1/accounts/gettoken');
        $token = $this->getEndpoint()->getLoginToken();

        $this->assertIsString($token);
        $this->assertEquals('344ebcaf34dff34', $token);
    }

    public function testGetSubscription()
    {
        $this->setAdapter('Endpoints/Account/getSubscription.json', '/api/prov/v1/accounts/subscription');
        $subscription = $this->getEndpoint()->getSubscription();

        $this->assertIsObject($subscription);

        $this->assertObjectHasAttribute('planStatus', $subscription);
        $this->assertIsObject($subscription->planStatus);
        $this->assertObjectHasAttribute('bandwidthHistory', $subscription);
        $this->assertIsArray($subscription->bandwidthHistory);
    }

    //
    // Managed Accounts
    //

    public function testGetManagedAccounts()
    {
        $this->setAdapter('Endpoints/Account/getAccounts.json', '/api/prov/v1/accounts/list');
        $accounts = $this->getEndpoint()->getManagedAccounts();

        $this->assertIsArray($accounts);
        $this->assertCount(2, $accounts);

        $this->assertObjectHasAttribute('email', $accounts[0]);
        $this->assertEquals('demo_account@imperva.com', $accounts[0]->email);
        $this->assertObjectHasAttribute('email', $accounts[1]);
        $this->assertEquals('demo_account2@imperva.com', $accounts[1]->email);

        $this->assertObjectHasAttribute('logins', $accounts[0]);
        $this->assertIsObject($accounts[0]->logins);

        $this->assertObjectHasAttribute('login_id', $accounts[0]->logins);
        $this->assertEquals(1243, $accounts[0]->logins->login_id);
    }

    public function testAddManagedAccount()
    {
        $this->setAdapter(
            'Endpoints/Account/addAccount.json',
            '/api/prov/v1/accounts/add',
            [
                'email' => 'demo_account@imperva.com'
            ]
        );

        $account = $this->getEndpoint()->addManagedAccount('demo_account@imperva.com', $this->getAccountConfig());

        $this->assertIsObject($account);

        $this->assertObjectHasAttribute('email', $account);
        $this->assertEquals('demo_account@imperva.com', $account->email);
    }

    public function testDeleteManagedAccount()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/accounts/delete',
            [
                'account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->deleteManagedAccount(12345);

        $this->assertTrue($result);
    }

    //
    // Sub Accounts
    //

    public function testGetSubAccounts()
    {
        $this->setAdapter('Endpoints/Account/getSubAccounts.json', '/api/prov/v1/accounts/listSubAccounts');
        $subAccounts = $this->getEndpoint()->getSubAccounts();

        $this->assertIsArray($subAccounts);
        $this->assertCount(2, $subAccounts);

        $this->assertObjectHasAttribute('sub_account_id', $subAccounts[0]);
        $this->assertEquals(123456, $subAccounts[0]->sub_account_id);
        $this->assertObjectHasAttribute('sub_account_name', $subAccounts[1]);
        $this->assertEquals('My super lovely sub account', $subAccounts[1]->sub_account_name);
    }

    public function testAddSubAccount()
    {
        $this->setAdapter(
            'Endpoints/Account/addSubAccount.json',
            '/api/prov/v1/subaccounts/add',
            [
                'sub_account_name' => 'Sub Account Name'
            ]
        );
        
        $subAccount = $this->getEndpoint()->addSubAccount('Sub Account Name', $this->getAccountConfig());

        $this->assertIsObject($subAccount);

        $this->assertObjectHasAttribute('sub_account_id', $subAccount);
        $this->assertEquals(123456, $subAccount->sub_account_id);
        $this->assertObjectHasAttribute('support_level', $subAccount);
        $this->assertEquals('Standard', $subAccount->support_level);
    }

    public function testDeleteSubAccount()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/subaccounts/delete',
            [
                'sub_account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->deleteSubAccount(12345);

        $this->assertTrue($result);
    }

    //
    // Configurations
    //

    public function testSetConfiguration()
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

        $result = $this->getEndpoint()->setConfiguration(12345, 'email', 'admin@example.com');

        $this->assertTrue($result);
    }

    //
    // Log Level
    //

    public function testSetLogLevel()
    {
        $this->setAdapter(
            'Endpoints/Account/setLogLevel.json',
            '/api/prov/v1/accounts/setlog',
            [
                'account_id' => 12345,
                'log_level' => 'full'
            ]
        );

        $result = $this->getEndpoint()->setLogLevel(12345, 'full');

        $this->assertTrue($result);
    }

    //
    // Log Storage
    //

    public function testTestS3Connection()
    {
        $this->setAdapter(
            'Endpoints/Account/testConnection.json',
            '/api/prov/v1/accounts/testS3Connection',
            [
                'account_id' => 12345,
                'bucket_name' => 'Test-Bucket-Name',
                'access_key' => 'Test-Access-Key',
                'secret_key' => 'Test-Secret-Key',
                'save_on_success' => true
            ]
        );

        $result = $this->getEndpoint()->testS3Connection(
            12345,
            'Test-Bucket-Name',
            'Test-Access-Key',
            'Test-Secret-Key',
            true
        );

        $this->assertTrue($result);
    }

    public function testTestSFTPConnection()
    {
        $this->setAdapter(
            'Endpoints/Account/testConnection.json',
            '/api/prov/v1/accounts/testSftpConnection',
            [
                'account_id' => 12345,
                'host' => '1.1.1.1',
                'user_name' => 'Test-Username',
                'password' => 'Test-Password',
                'destination_folder' => '/test/destination',
                'save_on_success' => true
            ]
        );

        $result = $this->getEndpoint()->testSFTPConnection(
            12345,
            '1.1.1.1',
            'Test-Username',
            'Test-Password',
            '/test/destination',
            true
        );

        $this->assertTrue($result);
    }

    public function testSetAmazonSiemStorage()
    {
        $this->setAdapter(
            'Endpoints/Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setAmazonSiemStorage',
            [
                'account_id' => 12345,
                'bucket_name' => 'Test-Bucket-Name',
                'access_key' => 'Test-Access-Key',
                'secret_key' => 'Test-Secret-Key'
            ]
        );

        $result = $this->getEndpoint()->setAmazomSiemStorage(
            12345,
            'Test-Bucket-Name',
            'Test-Access-Key',
            'Test-Secret-Key'
        );

        $this->assertTrue($result);
    }

    public function testSetSFTPSiemStorage()
    {
        $this->setAdapter(
            'Endpoints/Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setSftpSiemStorage',
            [
                'account_id' => 12345,
                'host' => '1.1.1.1',
                'user_name' => 'Test-Username',
                'password' => 'Test-Password',
                'destination_folder' => '/test/destination'
            ]
        );

        $result = $this->getEndpoint()->setSFTPSiemStorage(
            12345,
            '1.1.1.1',
            'Test-Username',
            'Test-Password',
            '/test/destination'
        );

        $this->assertTrue($result);
    }

    public function testSetDefaultSiemStorage()
    {
        $this->setAdapter(
            'Endpoints/Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setDefaultSiemStorage',
            [
                'account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->setDefaultSiemStorage(12345);

        $this->assertTrue($result);
    }

    //
    // Data Region
    //

    public function testGetDataStorageRegion()
    {
        $this->setAdapter(
            'Endpoints/Account/getDataStorageRegion.json',
            '/api/prov/v1/accounts/data-privacy/show',
            [
                'account_id' => 12345
            ]
        );

        $region = $this->getEndpoint()->getDataStorageRegion(12345);

        $this->assertEquals('EU', $region);
    }

    public function testSetDataStorageRegion()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/accounts/data-privacy/set-region-default',
            [
                'account_id' => 12345,
                'region' => 'EU'
            ]
        );

        $result = $this->getEndpoint()->setDataStorageRegion(12345, 'EU');

        $this->assertTrue($result);
    }
}
