<?php

namespace Incapsula\API\Endpoints;

class Domain extends Endpoint
{
    public function getEmails(int $site_id)
    {
        $options = [
            'site_id' => $site_id
        ];

        $query = $this->getAdapter()->request('/api/prov/v1/domain/emails', $options);

        $this->body = json_decode($query->getBody());
        return $this->body->domain_emails;
    }
}
