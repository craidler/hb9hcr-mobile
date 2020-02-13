<?php
namespace Logger;

use Laminas\Config\Config;

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = (new Config(include __DIR__ . '/../config/module.config.php'))->get(Module::class);
$stream = fopen($config->get('nmea')->get('device', 'dev/ttyACM0'), 'r');
$output = fopen(sprintf($config->get('nmea')->get('log'), $config->get('file')->get('path'), date('Ymd')),'a');
$needles = $config->get('nmea')->get('types');
$pattern = sprintf('#^\$.{2}(%s)\,#', implode('|', $needles));
$data = [];

do {
    $line = trim(fgets($stream));
    if (!preg_match($pattern, $line)) continue;
    $data[] = $line;
}
while (count($data) < count($needles));

fwrite($output, sprintf('%d:%s', time(), implode(':', $data)));
fclose($output);
fclose($stream);
