<?php

namespace Incapsula\API\Tests\Configurations;

class AccountTest extends \TestCase
{
    public function testToArray()
    {
        $account = new \Incapsula\API\Configurations\Account();
        $account->setEmail('admin@example.com');
        $parameters = $account->toArray();

        $this->assertCount(1, $parameters);

        $this->assertArrayHasKey('email', $parameters);
        $this->assertEquals('admin@example.com', $parameters['email']);
    }
}
