<?php

namespace Incapsula\API\Tests\Endpoints\Sites;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class CacheTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Cache($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testPurge()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/cache/purge',
            [
                'site_id' => 12345,
                'pattern' => 'testPattern',
                'tag_names' => 'test tag,tag2'
            ]
        );

        $result = $this->getEndpoint()->purge('testPattern', 'test tag,tag2');

        $this->assertTrue($result);
    }
}