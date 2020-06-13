<?php

namespace Incapsula\API\Endpoints\Sites\Performance;

use Incapsula\API\Endpoints\Sites\BaseClass;

class CacheMode extends BaseClass
{
    public function get(): string
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode/get', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->cache_mode;
    }

    public function set(
        string $mode,
        string $dyanmic = null,
        string $aggressive = null
    ): bool {
        $options = [
            'site_id' => $this->getSiteID(),
            'cache_mode' => $mode
        ];

        if (isset($dyanmic)) $options['dynamic_cache_duration'] = $dyanmic;
        if (isset($aggressive))  $options['aggressive_cache_duration'] = $aggressive;

        $query = $this->getAdapter()->request('/api/prov/v1/sites/performance/cache-mode', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}
