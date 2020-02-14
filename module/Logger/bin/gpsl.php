<?php
namespace Logger;

use Laminas\Config\Config;
use Logger\Service\Nmea;

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = (new Config(include __DIR__ . '/../config/module.config.php'))->get(Module::class)->get('nmea');
$service = new Nmea($config);

do {
    $item = $service->collect();
    var_dump($item->speed_m, $item->lat, $item->lat_i);
    sleep(60);
}
while (true);
