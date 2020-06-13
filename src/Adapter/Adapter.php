<?php

namespace Incapsula\API\Adapter;

use Incapsula\API\Parameters\Auth;
use Psr\Http\Message\ResponseInterface;

interface Adapter
{
    public function request(string $uri, ...$options): ResponseInterface;
}
