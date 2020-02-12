<?php
namespace Logger;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = fopen('/dev/ttyACM0', 'r');
while (true) {
    $line = trim(fgets($stream));
    if (strlen($line) && preg_match('#(GLL|VTG)#', $line)) var_dump($line);
}