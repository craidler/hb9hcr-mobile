<?php
namespace Logger;

use Laminas\Config\Config;
use Logger\Service\Nmea;

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = (new Config(include __DIR__ . '/../config/module.config.php'))->get(Module::class);
$service = new Nmea($config->get('nmea'));

do {
    $item = $service->collect();
    $output = fopen(sprintf('%s/%s.dat', $config->get('file')->get('path'), date('Ymd')), 'a');
    fwrite($output, sprintf('%s' . PHP_EOL, implode(',', $item->getArrayCopy())));
    fclose($output);
    sleep(5);
}
while (true);
