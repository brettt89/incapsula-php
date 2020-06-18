<?php

namespace Incapsula\API;

use Incapsula\API\Adapter;

interface API
{
    public function getAdapter(): Adapter;
}
