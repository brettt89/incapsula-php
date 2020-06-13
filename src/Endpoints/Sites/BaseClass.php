<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Endpoint;
use Incapsula\API\Adapter\Adapter;

class BaseClass extends Endpoint
{
    private $siteID;

    public function __construct(Adapter $adapter, int $site_id)
    {
        $this->siteID = $site_id;
        parent::__construct($adapter);
    }

    public function getSiteID()
    {
        return $this->siteID;
    }
}
