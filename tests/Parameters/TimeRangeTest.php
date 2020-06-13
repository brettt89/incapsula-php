<?php

namespace Incapsula\API\Tests\Parameters;

class TimeRangeTest extends \TestCase
{
    public function testGetRequestParameters()
    {
        $range    = new \Incapsula\API\Parameters\TimeRange();
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('today', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('today');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('today', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('last_7_days');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('last_7_days', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('last_30_days');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('last_30_days', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('last_90_days');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('last_90_days', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('month_to_date');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('month_to_date', $parameters['time_range']);

        $this->assertCount(1, $parameters);

        $range    = new \Incapsula\API\Parameters\TimeRange('custom', '123456789', '987654321');
        $parameters = $range->getRequestParameters();

        $this->assertArrayHasKey('time_range', $parameters);

        $this->assertEquals('custom', $parameters['time_range']);
        $this->assertEquals('123456789', $parameters['start']);
        $this->assertEquals('987654321', $parameters['end']);

        $this->assertCount(3, $parameters);
    }

    public function testErrors()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $range    = new \Incapsula\API\Parameters\TimeRange('not a range');
        $parameters = $range->getRequestParameters();

        $this->expectException(\InvalidArgumentException::class);
        
        $range    = new \Incapsula\API\Parameters\TimeRange(null);
        $parameters = $range->getRequestParameters();

        $this->expectException(\InvalidArgumentException::class);
        
        $range    = new \Incapsula\API\Parameters\TimeRange('custom', '12345');
        $parameters = $range->getRequestParameters();

        $this->expectException(\InvalidArgumentException::class);
        
        $range    = new \Incapsula\API\Parameters\TimeRange('custom', '12345abc', '-23452345');
        $parameters = $range->getRequestParameters();
    }
}
