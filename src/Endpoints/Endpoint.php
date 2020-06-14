<?php

namespace Incapsula\API\Endpoints;

use Incapsula\API\Interfaces\Endpoint as EndpointInterface;
use Incapsula\API\Adapter\Adapter;
use Incapsula\API\Traits\APITrait;

abstract class Endpoint implements EndpointInterface
{
    use APITrait;

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): Adapter
    {
        return $this->adapter;
    }
}