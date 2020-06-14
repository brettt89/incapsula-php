<?php

namespace Incapsula\API\Endpoints\Accounts;

use Incapsula\API\Endpoints\Endpoint;
use Incapsula\API\Adapter\Adapter;

class BaseClass extends Endpoint
{
    private $accountID;

    public function __construct(Adapter $adapter, int $account_id)
    {
        $this->accountID = $account_id;
        parent::__construct($adapter);
    }

    public function getAccountID()
    {
        return $this->accountID;
    }
}
