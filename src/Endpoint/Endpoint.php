<?php

namespace IncapsulaAPI\Endpoint;

use IncapsulaAPI\Endpoint\EndpointInterface;
use IncapsulaAPI\Adapter\AdapterInterface;

abstract class Endpoint implements EndpointInterface
{
    private $adapter;
    private $body;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}
