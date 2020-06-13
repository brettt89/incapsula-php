<?php

namespace Incapsula\API\Tests\Parameters;

class PaginationTest extends \TestCase
{
    public function testDefaultGetRequestParameters()
    {
        $site    = new \Incapsula\API\Parameters\Pagination();
        $parameters = $site->getRequestParameters();

        $this->assertArrayHasKey('page_size', $parameters);
        $this->assertArrayHasKey('page_num', $parameters);

        $this->assertEquals(50, $parameters['page_size']);
        $this->assertEquals(0, $parameters['page_num']);

        $this->assertCount(2, $parameters);

        $site    = new \Incapsula\API\Parameters\Pagination(100, 200);
        $parameters = $site->getRequestParameters();

        $this->assertArrayHasKey('page_size', $parameters);
        $this->assertArrayHasKey('page_num', $parameters);

        $this->assertEquals(100, $parameters['page_size']);
        $this->assertEquals(200, $parameters['page_num']);

        $this->assertCount(2, $parameters);
    }
}
