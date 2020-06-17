<?php

namespace Incapsula\API\Interfaces;

use Incapsula\API\Interfaces\Adapter;

interface Endpoint
{
    public function getAdapter(): Adapter;
}
