<?php

namespace Incapsula\API\Tests\Interfaces;

use \Incapsula\API\Interfaces\Endpoint;

interface TestEndpoint
{
    public function getEndpoint(): Endpoint;
}
