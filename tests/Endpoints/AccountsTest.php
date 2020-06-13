<?php

namespace Incapsula\API\Tests\Endpoints;

use Incapsula\API\Tests\Interfaces\TestConfigure;
use Incapsula\API\Tests\Interfaces\TestEndpoint;

class AccountTest extends \TestCase implements TestConfigure, TestEndpoint
{
    private $endpoint;
    private $config;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Accounts($this->getAdapter());
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

    public function testToken()
    {
        $this->setAdapter('Endpoints/Account/getLoginToken.json', '/api/prov/v1/accounts/gettoken');
        $token = $this->getEndpoint()->getToken();

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

    public function testGetList()
    {
        $this->setAdapter('Endpoints/Account/getAccounts.json', '/api/prov/v1/accounts/list');
        $accounts = $this->getEndpoint()->getList();

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

    public function testAdd()
    {
        $this->setAdapter(
            'Endpoints/Account/addAccount.json',
            '/api/prov/v1/accounts/add',
            [
                'email' => 'demo_account@imperva.com'
            ]
        );

        $account = $this->getEndpoint()->add('demo_account@imperva.com', $this->getConfig());

        $this->assertIsObject($account);

        $this->assertObjectHasAttribute('email', $account);
        $this->assertEquals('demo_account@imperva.com', $account->email);
    }

    public function testDelete()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/accounts/delete',
            [
                'account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->delete(12345);

        $this->assertTrue($result);
    }

    //
    // Logs
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
}
