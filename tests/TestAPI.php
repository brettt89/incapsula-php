<?php

namespace Incapsula\API\Tests;

use Incapsula\API\API;

interface TestAPI
{
    public function getEndpoint(): API;
}
