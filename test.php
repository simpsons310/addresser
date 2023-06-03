<?php

require_once __DIR__ . '/vendor/autoload.php';

use Addresser\Addresser;

$addresser = new Addresser();
// var_dump($addresser->getAddresses());
// var_dump($addresser->getProvinces());
// var_dump($addresser->getDistricts());
var_dump($addresser->getWards());
