<?php

namespace Incapsula\API\Endpoints\Sites\Performance;

use Incapsula\API\Endpoints\Sites\BaseClass;

class StaleContent extends BaseClass
{
    public function get(): \stdClass
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body;
    }

    public function set(
        bool $stale = false,
        string $mode = null,
        int $time = null,
        string $time_unit = null
    ): bool
    {
        $options = [
            'site_id' => $this->getSiteID(),
            'serve_stale_content' => $stale
        ];

        if (isset($mode)) $options['stale_content_mode'] = $mode;
        if (isset($time)) $options['time'] = $time;
        if (isset($time_unit)) $options['time_unit'] = $time_unit;

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/stale-content', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}
