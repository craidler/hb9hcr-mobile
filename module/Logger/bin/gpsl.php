<?php
namespace Logger;

use Laminas\Config\Config;
use Logger\Model\Nmea\Log;
use Logger\Service\Nmea;

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = (new Config(include __DIR__ . '/../config/module.config.php'))->get(Module::class);
$service = new Nmea($config->get('nmea'));

do {
    $item = Log::createFromItem($service->collect());
    $output = fopen(sprintf('%s/%s.dat', $config->get('file')->get('path'), gmdate('Ymd')), 'a');
    fwrite($output, sprintf('%s' . PHP_EOL, implode(',', $item->getArrayCopy())));
    fclose($output);
    sleep($config->get('nmea')->get('interval'));
}
while (true);
