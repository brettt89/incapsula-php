<?php

namespace IncapsulaAPI\Endpoint;

use IncapsulaAPI\Endpoint\Endpoint;

// @todo - Error from Incapsula - "Operation not allowed"
class DDoSProtection extends Endpoint
{
    public function addOriginIP(
        string $origin_ip,
        bool $enable_ha_protocol = false,
        string $description = null
    ): \stdClass {
        $options = [
            'origin_ip' => $origin_ip,
            'enable_ha_protocol' => $enable_ha_protocol
        ];

        if (isset($description)) {
            $options['description'] = $description;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/add/ip', $options);
        return $this->body;
    }

    public function addOriginCNAME(
        string $cname,
        bool $enable_ha_protocol = false,
        string $description = null
    ): \stdClass {
        $options = [
            'cname' => $cname,
            'enable_ha_protocol' => $enable_ha_protocol
        ];

        if (isset($description)) {
            $options['description'] = $description;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/add/cname', $options);
        return $this->body;
    }

    public function addOriginDNSIP(
        string $dns_name,
        string $origin_ip,
        bool $disable_dns_check = true,
        bool $enable_ha_protocol = false,
        string $description = null
    ): \stdClass {
        $options = [
            'dns_name' => $dns_name,
            'origin_ip' => $origin_ip,
            'disable_dns_check' => $disable_dns_check,
            'enable_ha_protocol' => $enable_ha_protocol
        ];

        if (isset($description)) {
            $options['description'] = $description;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/add/dns-with-ip', $options);
        return $this->body;
    }

    public function addOriginDNSCNAME(
        string $dns_name,
        string $cname,
        bool $disable_dns_check = true,
        bool $enable_ha_protocol = false,
        string $description = null
    ): \stdClass {
        $options = [
            'dns_name' => $dns_name,
            'cname' => $cname,
            'disable_dns_check' => $disable_dns_check,
            'enable_ha_protocol' => $enable_ha_protocol
        ];

        if (isset($description)) {
            $options['description'] = $description;
        }

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/add/dns-with-cname', $options);
        return $this->body;
    }

    public function modifyOriginIP(
        string $origin_ip,
        string $edge_ip
    ): \stdClass {
        $options = [
            'origin_ip' => $origin_ip,
            'edge_ip' => $edge_ip
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/ip', $options);
        return $this->body;
    }

    public function modifyOriginCNAME(
        string $origin_ip,
        string $cname
    ): \stdClass {
        $options = [
            'origin_ip' => $origin_ip,
            'cname' => $cname
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/cname', $options);
        return $this->body;
    }

    public function modifyOriginDNSIP(
        string $origin_ip,
        string $dns_name,
        string $edge_ip,
        bool $disable_dns_check = true
    ): \stdClass {
        $options = [
            'origin_ip' => $origin_ip,
            'dns_name' => $dns_name,
            'edge_ip' => $edge_ip,
            'disable_dns_check' => $disable_dns_check
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/dns-with-ip', $options);
        return $this->body;
    }

    public function modifyOriginDNSCNAME(
        string $cname,
        string $dns_name,
        string $edge_ip,
        bool $disable_dns_check = true
    ): \stdClass {
        $options = [
            'cname' => $cname,
            'dns_name' => $dns_name,
            'edge_ip' => $edge_ip,
            'disable_dns_check' => $disable_dns_check
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/dns-with-cname', $options);
        return $this->body;
    }

    public function enableHAProtocol(string $edge_ip): bool
    {
        $options = [
            'edge_ip' => $edge_ip,
            'enable_ha_protocol' => true
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/ha-protocol', $options);
        return empty((array) $this->body);
    }

    public function disableHAProtocol(string $edge_ip): bool
    {
        $options = [
            'edge_ip' => $edge_ip,
            'enable_ha_protocol' => false
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/edit/ha-protocol', $options);
        return empty((array) $this->body);
    }

    public function deleteEdgeIP(string $edge_ip): bool
    {
        $options = [
            'edge_ip' => $edge_ip
        ];

        $this->body = $this->getAdapter()->request('/api/prov/v1/ddos-protection/edge-ip/remove', $options);
        return empty((array) $this->body);
    }
}
