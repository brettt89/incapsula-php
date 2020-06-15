<?php

namespace Incapsula\API\Tests\Adapter;

class IncapsulaExceptionTest extends \TestCase
{
    public function testEmptyMessage()
    {
        $exception = new \Incapsula\API\Adapter\IncapsulaException(null, 1);
        $this->assertEquals('Unexpected error', $exception->getMessage());
    }
}
