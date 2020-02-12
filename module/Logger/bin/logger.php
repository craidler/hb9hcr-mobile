<?php
namespace Logger;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = fopen('/dev/ttyACM0', 'r');
while (true) {
    var_dump(fgets($stream));
    sleep(1);
}