<?php

require __DIR__ . '/vendor/autoload.php';

$auth     = new Incapsula\API\Parameters\Auth('34369', 'b3ee40d5-7693-467a-bbca-7d5e146722b0');
$adapter = new Incapsula\API\Adapter\Guzzle($auth);
$user    = new Incapsula\API\Endpoints\Sites($adapter);
    
$test = $user->listSites();

var_dump($test);