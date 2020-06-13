<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Adapter\Adapter;

interface Endpoint
{
    public function __construct(Adapter $adapter);
}
