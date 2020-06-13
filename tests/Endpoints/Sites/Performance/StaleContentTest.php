<?php

namespace Incapsula\API\Tests\Endpoints\Sites\Performance;

use Incapsula\API\Tests\Interfaces\TestEndpoint;

class StaleContentTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Sites\Performance\StaleContent($this->getAdapter(), 12345);
        }
        return $this->endpoint;
    }
    
    public function testGet()
    {
        $this->setAdapter(
            'Endpoints/Site/getStaleContentSettings.json',
            '/api/prov/v1/sites/performance/stale-content/get',
            [
                'site_id' => 12345
            ]
        );

        $mode = $this->getEndpoint()->get();

        $this->assertIsObject($mode);
    }

    public function testSet()
    {
        $this->setAdapter(
            'Endpoints/success.json',
            '/api/prov/v1/sites/performance/stale-content',
            [
                'site_id' => 12345,
                'serve_stale_content' => true,
                'stale_content_mode' => 'ADAPTIVE',
                'time' => 5,
                'time_unit' => 'MINUTES'
            ]
        );

        $result = $this->getEndpoint()->set(true, 'ADAPTIVE', 5, 'MINUTES');

        $this->assertTrue($result);
    }
}