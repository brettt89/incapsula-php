<?php

namespace Incapsula\API\Tests\Endpoints;

use \Incapsula\API\Tests\Interfaces\TestEndpoint;

class DomainTest extends \TestCase implements TestEndpoint
{
    private $endpoint;

    public function getEndpoint(): \Incapsula\API\Interfaces\Endpoint
    {
        if (!isset($this->endpoint)) {
            $this->endpoint = new \Incapsula\API\Endpoints\Domain($this->getAdapter());
        }
        return $this->endpoint;
    }
    
    public function testGetEmails()
    {
        $this->setAdapter(
            'Endpoints/Domain/getEmails.json',
            '/api/prov/v1/domain/emails',
            [
                'site_id' => 12345,
            ]
        );
        $emails = $this->getEndpoint()->getEmails(12345);

        $this->assertIsArray($emails);
        $this->assertCount(2, $emails);

        $this->assertEquals('admin@example.com', $emails[0]);
        $this->assertEquals('webmaster@example.com', $emails[1]);
    }
}
