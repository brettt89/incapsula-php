<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class RulesTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Site\Rules($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testAddIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/add',
            [
                'site_id' => 12345,
                'name' => 'test name',
                'action' => 'RULE_ACTION_REDIRECT',
                'filter' => 'test Filter',
                'response_code' => 312
            ]
        );

        $result = $this->getEndpoint()->addIncapRule(12345, 'test name', 'RULE_ACTION_REDIRECT', [
            'filter' => 'test Filter',
            'response_code' => 312
        ]);

        $this->assertIsObject($result);
    }

    public function testUpdateIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/edit',
            [
                'rule_id' => 98765,
                'action' => 'RULE_ACTION_REDIRECT',
                'filter' => 'test Filter',
                'response_code' => 312
            ]
        );

        $result = $this->getEndpoint()->updateIncapRule(98765, 'RULE_ACTION_REDIRECT', [
            'filter' => 'test Filter',
            'response_code' => 312
        ]);

        $this->assertIsObject($result);
    }

    public function testEnableDisableIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/enableDisable',
            [
                'rule_id' => 98765,
                'enabled' => false
            ]
        );

        $result = $this->getEndpoint()->enableDisableIncapRule(98765, false);

        $this->assertIsObject($result);
    }

    public function testDelteIncapRule()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/delete',
            [
                'rule_id' => 98765
            ]
        );

        $result = $this->getEndpoint()->deleteIncapRule(98765);

        $this->assertIsObject($result);
    }

    public function testListIncapRules()
    {
        $this->setAdapter(
            'Endpoints/Site/listIncapRules.json',
            '/api/prov/v1/sites/incapRules/list',
            [
                'site_id' => 12345,
                'include_ad_rules' => true,
                'include_incap_rules' => false,
                'page_size' => 100,
                'page_num' => 2
            ]
        );

        $result = $this->getEndpoint()->listIncapRules(12345, true, false, [
            'page_size' => 100,
            'page_num' => 2
        ]);

        $this->assertIsObject($result);
    }

    public function testListAccountIncapRules()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/account/list',
            [
                'account_id' => 12345,
                'include_ad_rules' => true,
                'include_incap_rules' => false
            ]
        );

        $result = $this->getEndpoint()->listAccountIncapRules(12345, true, false);

        $this->assertIsObject($result);
    }

    public function testSetIncapRulePriority()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/incapRules/priority/set',
            [
                'rule_id' => 98765,
                'priority' => 10
            ]
        );

        $result = $this->getEndpoint()->setIncapRulePriority(98765, 10);

        $this->assertIsObject($result);
    }
}