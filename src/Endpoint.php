<?php

namespace Incapsula\API;

use Incapsula\API\API;
use Incapsula\API\Adapter;

abstract class Endpoint implements API
{
    private $adapter;
    private $body;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): Adapter
    {
        return $this->adapter;
    }
}
