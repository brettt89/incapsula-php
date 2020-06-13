# Incapsula SDK (v1 API Binding for PHP 7)

[![PHP Composer](https://github.com/brettt89/incapsula-php/workflows/PHP%20Composer/badge.svg?branch=master)](https://github.com/brettt89/incapsula-php)

## Installation

The recommended way to install this package is via the Packagist Dependency Manager ([brettt89/incapsula-sdk](https://packagist.org/packages/brettt89/incapsula-sdk)).

```bash
$ composer require brettt89/incapsula-sdk
```

## Incapsula API version 1

The Incapsula API can be found [here](https://docs.imperva.com/bundle/cloud-application-security/page/api/api.htm).
Each API call is provided via a similarly named function within various classes in the **Incapsula\API\Endpoints** namespace:

- [x] [Account](https://docs.imperva.com/bundle/cloud-application-security/page/api/accounts-api.htm)
- [ ] [Sites](https://docs.imperva.com/bundle/cloud-application-security/page/api/sites-api.htm)
- [ ] [DDoS Protection](https://docs.imperva.com/bundle/cloud-application-security/page/api/ddos-for-networks.htm)
- [ ] [Traffic Statistics and Details](https://docs.imperva.com/bundle/cloud-application-security/page/api/traffic-api.htm)
- [ ] [Login Protect](https://docs.imperva.com/bundle/cloud-application-security/page/api/login-protect-api.htm)
- [ ] [Integration API](https://docs.imperva.com/bundle/cloud-application-security/page/api/integration-api.htm)
- [ ] [Infrastructure Protection Test Alerts](https://docs.imperva.com/bundle/cloud-application-security/page/api/network-ddos-api.htm)

Note that this repository is currently under development, additional classes and endpoints being actively added.

## Getting Started

```php
$key        = new Incapsula\API\Parameters\Auth('Api-ID', 'Api-Key');
$adapter    = new Incapsula\API\Adapter\Guzzle($key);
$account    = new Incapsula\API\Endpoints\Account($adapter);

$account_id = 123456;
    
print_r($account->getStatus($account_id));
```
