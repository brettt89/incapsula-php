<?php

namespace Incapsula\API\Endpoints\Sites;

use Incapsula\API\Endpoints\Sites\BaseClass;

class Cache extends BaseClass
{   
    public function purge(string $pattern = null, string $tag_names = null): bool
    {
        $options = [
            'site_id' => $this->getSiteID()
        ];

        if (isset($pattern)) $options['pattern'] = $pattern;
        if (isset($tag_names)) $options['tag_names'] = $tag_names;

        $query = $this->getAdapter()->request('/api/prov/v1/sites/cache/purge', $options);

        $this->body = json_decode($query->getBody());
        return empty((array) $this->body);
    }
}
