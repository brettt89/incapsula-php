<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Endpoint;
use Incapsula\API\Adapter\Adapter;

abstract class BaseClass extends Endpoint
{
    private $site_id;

    public function __construct(Adapter $adapter, int $site_id)
    {
        $this->setSiteID($site_id);
        parent::__construct($adapter);
    }

    public function setSiteID(int $site_id)
    {
        $this->site_id = $site_id;
    }

    public function getSiteID(): int
    {
        return $this->site_id;
    }
}
