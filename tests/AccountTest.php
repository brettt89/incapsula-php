<?php

namespace Incapsula\API\Tests;

use Incapsula\API\Tests\TestAPI;

class AccountTest extends \TestCase implements TestAPI
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\API
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Account($this->getAdapter());
        }
        return $this->endpoint;
    }

    //
    // Account Controls
    //

    public function testGetStatus()
    {
        $this->setAdapter('Account/getStatus.json', '/api/prov/v1/account');
        $status = $this->getEndpoint()->getStatus();

        $this->assertIsObject($status);

        $this->assertObjectHasAttribute('email', $status);
        $this->assertEquals('demo_account@imperva.com', $status->email);
        $this->assertObjectHasAttribute('plan_name', $status);
        $this->assertEquals('Enterprise', $status->plan_name);
    }

    public function testGetLoginToken()
    {
        $this->setAdapter('Account/getLoginToken.json', '/api/prov/v1/accounts/gettoken');
        $token = $this->getEndpoint()->getLoginToken();

        $this->assertIsString($token);
        $this->assertEquals('344ebcaf34dff34', $token);
    }

    public function testGetSubscriptionDetails()
    {
        $this->setAdapter(
            'Account/getSubscription.json',
            '/api/prov/v1/accounts/subscription',
            [
                'account_id' => 12345
            ]
        );
        $subscription = $this->getEndpoint()->getSubscriptionDetails(12345);

        $this->assertIsObject($subscription);

        $this->assertObjectHasAttribute('planStatus', $subscription);
        $this->assertIsObject($subscription->planStatus);
        $this->assertObjectHasAttribute('bandwidthHistory', $subscription);
        $this->assertIsArray($subscription->bandwidthHistory);
    }

    public function testModifyConfig()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/accounts/configure',
            [
                'account_id' => 12345,
                'param' => 'email',
                'value' => 'admin@example.com'
            ]
        );

        $result = $this->getEndpoint()->modifyConfig(12345, 'email', 'admin@example.com');

        $this->assertTrue($result);
    }

    //
    // Managed Accounts
    //

    public function testGetManagedAccounts()
    {
        $this->setAdapter('Account/getAccounts.json', '/api/prov/v1/accounts/list');
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

    public function testCreateManagedAccount()
    {
        $this->setAdapter(
            'Account/addAccount.json',
            '/api/prov/v1/accounts/add',
            [
                'email' => 'demo_account@imperva.com',
                'user_name' => 'John Doe'
            ]
        );

        $account = $this->getEndpoint()->createManagedAccount('demo_account@imperva.com', [
            'user_name' => 'John Doe'
        ]);

        $this->assertIsObject($account);

        $this->assertObjectHasAttribute('email', $account);
        $this->assertEquals('demo_account@imperva.com', $account->email);
    }

    public function testDeleteManagedAccount()
    {
        $this->setAdapter(
            'success.json',
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
        $this->setAdapter(
            'Account/getSubAccounts.json',
            '/api/prov/v1/accounts/listSubAccounts',
            [
                'account_id' => 12345,
                'page_size' => 100,
                'page_num' => 2
            ]
        );
        $subAccounts = $this->getEndpoint()->getSubAccounts(12345, [
            'page_size' => 100,
            'page_num' => 2
        ]);

        $this->assertIsArray($subAccounts);
        $this->assertCount(2, $subAccounts);

        $this->assertObjectHasAttribute('sub_account_id', $subAccounts[0]);
        $this->assertEquals(123456, $subAccounts[0]->sub_account_id);
        $this->assertObjectHasAttribute('sub_account_name', $subAccounts[1]);
        $this->assertEquals('My super lovely sub account', $subAccounts[1]->sub_account_name);
    }

    public function testCreateSubAccount()
    {
        $this->setAdapter(
            'Account/addSubAccount.json',
            '/api/prov/v1/subaccounts/add',
            [
                'sub_account_name' => 'Sub Account Name',
                'user_name' => 'John Doe'
            ]
        );
        
        $subAccount = $this->getEndpoint()->createSubAccount('Sub Account Name', [
            'user_name' => 'John Doe'
        ]);

        $this->assertIsObject($subAccount);

        $this->assertObjectHasAttribute('sub_account_id', $subAccount);
        $this->assertEquals(123456, $subAccount->sub_account_id);
        $this->assertObjectHasAttribute('support_level', $subAccount);
        $this->assertEquals('Standard', $subAccount->support_level);
    }

    public function testDeleteSubAccount()
    {
        $this->setAdapter(
            'success.json',
            '/api/prov/v1/subaccounts/delete',
            [
                'sub_account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->deleteSubAccount(12345);

        $this->assertTrue($result);
    }

    //
    // Logs
    //

    public function testModifyLogLevel()
    {
        $this->setAdapter(
            'Account/setLogLevel.json',
            '/api/prov/v1/accounts/setlog',
            [
                'account_id' => 12345,
                'log_level' => 'full'
            ]
        );

        $result = $this->getEndpoint()->modifyLogLevel(12345, 'full');

        $this->assertTrue($result);
    }

    public function testTestS3Connection()
    {
        $this->setAdapter(
            'Account/testConnection.json',
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

        $this->assertIsObject($result);
    }

    public function testTestSFTPConnection()
    {
        $this->setAdapter(
            'Account/testConnection.json',
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

        $this->assertIsObject($result);
    }

    public function testModifyAmazonSiemStorage()
    {
        $this->setAdapter(
            'Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setAmazonSiemStorage',
            [
                'account_id' => 12345,
                'bucket_name' => 'Test-Bucket-Name',
                'access_key' => 'Test-Access-Key',
                'secret_key' => 'Test-Secret-Key'
            ]
        );

        $result = $this->getEndpoint()->modifyAmazomSiemStorage(
            12345,
            'Test-Bucket-Name',
            'Test-Access-Key',
            'Test-Secret-Key'
        );

        $this->assertIsObject($result);
    }

    public function testModifySFTPSiemStorage()
    {
        $this->setAdapter(
            'Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setSftpSiemStorage',
            [
                'account_id' => 12345,
                'host' => '1.1.1.1',
                'user_name' => 'Test-Username',
                'password' => 'Test-Password',
                'destination_folder' => '/test/destination'
            ]
        );

        $result = $this->getEndpoint()->modifySFTPSiemStorage(
            12345,
            '1.1.1.1',
            'Test-Username',
            'Test-Password',
            '/test/destination'
        );

        $this->assertIsObject($result);
    }

    public function testModifyDefaultSiemStorage()
    {
        $this->setAdapter(
            'Account/setSiemStorage.json',
            '/api/prov/v1/accounts/setDefaultSiemStorage',
            [
                'account_id' => 12345
            ]
        );

        $result = $this->getEndpoint()->modifyDefaultSiemStorage(12345);

        $this->assertTrue($result);
    }

    public function testGetDefaultStorageRegion()
    {
        $this->setAdapter(
            'Account/getDataStorageRegion.json',
            '/api/prov/v1/accounts/data-privacy/show',
            [
                'account_id' => 12345
            ]
        );

        $region = $this->getEndpoint()->getDefaultStorageRegion(12345);

        $this->assertEquals('EU', $region);
    }

    public function testModifyDefaultStorageRegion()
    {
        $this->setAdapter(
            'Account/getDataStorageRegion.json',
            '/api/prov/v1/accounts/data-privacy/set-region-default',
            [
                'account_id' => 12345,
                'data_storage_region' => 'EU'
            ]
        );

        $result = $this->getEndpoint()->modifyDefaultStorageRegion(12345, 'EU');

        $this->assertTrue($result);
    }
}
