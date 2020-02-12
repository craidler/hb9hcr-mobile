<?php
namespace Logger;

use Exception;
use Logger\Model\Entry;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = fopen('/dev/ttyACM0', 'r');
stream_set_blocking($stream, true);

while (true) {
    try {
        $data = trim(fgets($stream));
        $match = null;

        if (preg_match('#\$.{2}(GGA)#', $data, $match)) {
            $entry = Entry::createFromNMEA($data);
            printf('GGA Sats: %d Altitude: %d GEOS: %.02f HDOP: %.02f', $entry->sats, $entry->alt, $entry->geos, $entry->hdop) . PHP_EOL;
        }
    }
    catch (Exception $e) {
        print $e->getMessage() . PHP_EOL;
    }
}