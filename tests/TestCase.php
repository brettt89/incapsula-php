<?php

use GuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class TestCase extends BaseTestCase
{
    private $adapter;
    
    /**
     * Returns a PSR7 Stream for a given fixture.
     *
     * @param  string     $fixture The fixture to create the stream for.
     * @return Psr7\Stream
     */
    protected function getPsr7StreamForFixture($fixture): Psr7\Stream
    {
        $path = sprintf('%s/Fixtures/%s', __DIR__, $fixture);

        $this->assertFileExists($path);

        $stream = Psr7\stream_for(file_get_contents($path));

        $this->assertInstanceOf(Psr7\Stream::class, $stream);

        return $stream;
    }

    /**
     * Returns a PSR7 Response (JSON) for a given fixture.
     *
     * @param  string        $fixture    The fixture to create the response for.
     * @param  integer       $statusCode A HTTP Status Code for the response.
     * @return Psr7\Response
     */
    protected function getPsr7JsonResponseForFixture($fixture, $statusCode = 200): Psr7\Response
    {
        $stream = $this->getPsr7StreamForFixture($fixture);

        $this->assertNotNull(json_decode($stream));
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        return new Psr7\Response($statusCode, ['Content-Type' => 'application/json'], $stream);
    }
    
    protected function setAdapter(string $fixture, string $uri = null, array $options = null)
    {
        $response = $this->getPsr7JsonResponseForFixture($fixture);

        $this->adapter = $this->getMockBuilder(\Incapsula\API\Interfaces\Adapter::class)
            ->setMethods(['request'])
            ->getMock();
        
        $this->adapter->method('request')->willReturn(json_decode($response->getBody()));

        if ($uri !== null) {
            if ($options !== null) {
                $this->adapter->expects($this->once())
                    ->method('request')
                    ->with(
                        $this->equalTo($uri),
                        $this->equalTo($options)
                    );
            } else {
                $this->adapter->expects($this->once())
                    ->method('request')
                    ->with(
                        $this->equalTo($uri)
                    );
            }
        }
    }

    protected function getAdapter()
    {
        if (!$this->adapter) {
            $this->setAdapter('Endpoints/success.json');
        }
        return $this->adapter;
    }
}
