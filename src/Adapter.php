<?php

namespace Incapsula\API;

interface Adapter
{
    public function request(string $uri, ...$options): \stdClass;
}
