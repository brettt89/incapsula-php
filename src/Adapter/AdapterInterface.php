<?php

namespace IncapsulaAPI\Adapter;

interface AdapterInterface
{
    public function request(string $uri, ...$options): \stdClass;
}
