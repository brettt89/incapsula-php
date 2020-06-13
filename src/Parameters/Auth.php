<?php

namespace Incapsula\API\Parameters;

use Incapsula\API\Interfaces\Parameter;

class Auth implements Parameter
{
    private $apiId;
    private $apiKey;

    public function __construct(string $apiId, string $apiKey)
    {
        $this->apiId  = $apiId;
        $this->apiKey = $apiKey;
    }
    
    public function getRequestParameters(): array
    {
        return [
            'api_id'   => $this->apiId,
            'api_key' => $this->apiKey
        ];
    }
}
