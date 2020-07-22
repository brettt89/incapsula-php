<?php

namespace IncapsulaAPI\Test\Endpoint;

use IncapsulaAPI\Endpoint\EndpointInterface;

interface TestEndpointInterface
{
    public function getEndpoint(): EndpointInterface;
}
