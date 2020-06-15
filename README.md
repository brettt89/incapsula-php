# Incapsula SDK (v1 API Binding for PHP 7)

[![PHP Composer](https://github.com/brettt89/incapsula-php/workflows/PHP%20Composer/badge.svg?branch=master)](https://github.com/brettt89/incapsula-php)
[![codecov](https://codecov.io/gh/brettt89/incapsula-php/branch/master/graph/badge.svg)](https://codecov.io/gh/brettt89/incapsula-php)


## Installation

NOTE: This is current in Development. No tagged versions are available at this current point in time. Hopefully we will have enough functionality to tag a release soon.

The recommended way to install this package is via the Packagist Dependency Manager ([brettt89/incapsula-sdk](https://packagist.org/packages/brettt89/incapsula-sdk)).

```bash
$ composer require brettt89/incapsula-sdk dev-master
```

## Incapsula API version 1

The Incapsula API can be found [here](https://docs.imperva.com/bundle/cloud-application-security/page/api/api.htm).
Each API call is provided via a similarly named function within various classes in the **Incapsula\API\Endpoints** namespace:

- [x] [Account](https://docs.imperva.com/bundle/cloud-application-security/page/api/accounts-api.htm)
- [x] [Sites](https://docs.imperva.com/bundle/cloud-application-security/page/api/sites-api.htm)
- [ ] [DDoS Protection](https://docs.imperva.com/bundle/cloud-application-security/page/api/ddos-for-networks.htm) (coming soon)
- [ ] [Traffic Statistics and Details](https://docs.imperva.com/bundle/cloud-application-security/page/api/traffic-api.htm) (coming soon)
- [ ] [Login Protect](https://docs.imperva.com/bundle/cloud-application-security/page/api/login-protect-api.htm) (coming soon)
- [ ] [Integration API](https://docs.imperva.com/bundle/cloud-application-security/page/api/integration-api.htm) (coming soon)
- [ ] [Infrastructure Protection Test Alerts](https://docs.imperva.com/bundle/cloud-application-security/page/api/network-ddos-api.htm) (coming soon)

Note that this repository is currently under development, additional classes and endpoints being actively added.

## Getting Started

```php
$key        = new Incapsula\API\Parameters\Auth('Api-ID', 'Api-Key');
$adapter    = new Incapsula\API\Adapter\Guzzle($key);
$account    = new Incapsula\API\Endpoints\Account($adapter);

$account_id = 123456;
    
print_r($account->listSites($account_id));
```
