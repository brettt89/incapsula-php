# Incapsula SDK (v1 API Binding for PHP 7)

[![PHP Composer](https://github.com/brettt89/incapsula-php/workflows/PHP%20Composer/badge.svg?branch=master)](https://github.com/brettt89/incapsula-php)
[![codecov](https://codecov.io/gh/brettt89/incapsula-php/branch/master/graph/badge.svg)](https://codecov.io/gh/brettt89/incapsula-php)


## Installation

The recommended way to install this package is via the Packagist Dependency Manager ([brettt89/incapsula-api-php](https://packagist.org/packages/brettt89/incapsula-api-php)).

```bash
$ composer require brettt89/incapsula-api-php
```

## Incapsula API version 1

The Incapsula API can be found [here](https://docs.imperva.com/bundle/cloud-application-security/page/api/api.htm).
Each API call is provided via a similarly named function within various classes in the **IncapsulaAPI** namespace:

- [x] [Account](https://docs.imperva.com/bundle/cloud-application-security/page/api/accounts-api.htm)
- [x] [Sites](https://docs.imperva.com/bundle/cloud-application-security/page/api/sites-api.htm)
- [x] [DDoS Protection](https://docs.imperva.com/bundle/cloud-application-security/page/api/ddos-for-networks.htm)
- [ ] [Traffic Statistics and Details](https://docs.imperva.com/bundle/cloud-application-security/page/api/traffic-api.htm)
- [ ] [Login Protect](https://docs.imperva.com/bundle/cloud-application-security/page/api/login-protect-api.htm)
- [ ] [Integration API](https://docs.imperva.com/bundle/cloud-application-security/page/api/integration-api.htm)
- [ ] [Infrastructure Protection Test Alerts](https://docs.imperva.com/bundle/cloud-application-security/page/api/network-ddos-api.htm)

Note that this repository is currently under development, additional endpoints are being actively added.

## Getting Started

```php
$key        = new IncapsulaAPI\Auth\ApiKey('Api-ID', 'Api-Key');
$adapter    = new IncapsulaAPI\Adapter\Guzzle($key);
$account    = new IncapsulaAPI\Endpoint\Account($adapter);

$account_id = 123456;
    
print_r($account->getSites($account_id));
```

## Contributions

Please submit any contributions as a pull request to the `master` branch.
