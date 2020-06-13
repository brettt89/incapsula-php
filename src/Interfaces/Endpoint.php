<?php

namespace Incapsula\API\Interfaces;

use Incapsula\API\Adapter\Adapter;

interface Endpoint
{
    public function getAdapter(): Adapter;
}
