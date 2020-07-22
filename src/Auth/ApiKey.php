<?php

namespace IncapsulaAPI\Auth;

use IncapsulaAPI\Auth\AuthInterface;

class ApiKey implements AuthInterface
{
    private $apiId;
    private $apiKey;

    public function __construct(string $apiId, string $apiKey)
    {
        $this->apiId  = $apiId;
        $this->apiKey = $apiKey;
    }
    
    public function toArray(): array
    {
        return [
            'api_id'   => $this->apiId,
            'api_key' => $this->apiKey
        ];
    }
}
