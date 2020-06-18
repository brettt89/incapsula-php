<?php

namespace Incapsula\API;

use Incapsula\API\Auth;

class ApiKey implements Auth
{
    private $apiId;
    private $apiKey;

    public function __construct(string $apiId, string $apiKey)
    {
        $this->apiId  = $apiId;
        $this->apiKey = $apiKey;
    }
    
    public function toArray()
    {
        return [
            'api_id'   => $this->apiId,
            'api_key' => $this->apiKey
        ];
    }
}
