<?php

namespace Incapsula\API\Interfaces;

interface Adapter
{
    public function request(string $uri, ...$options): \stdClass;
}
