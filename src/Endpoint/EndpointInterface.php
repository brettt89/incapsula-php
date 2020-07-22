<?php

namespace IncapsulaAPI\Endpoint;

use IncapsulaAPI\Adapter\AdapterInterface;

interface EndpointInterface
{
    public function getAdapter(): AdapterInterface;
}
