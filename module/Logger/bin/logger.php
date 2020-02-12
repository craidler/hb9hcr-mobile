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

        if (preg_match('#^\$GN(GGA)#', $data)) {
            $entry = Entry::createFromNMEA($data);
            printf('GGA Sats: %d Altitude: %d GEOS: %.02f HDOP: %.02f' . PHP_EOL, $entry->sats, $entry->alt, $entry->geos, $entry->hdop);
        }
    }
    catch (Exception $e) {
        print $e->getMessage() . PHP_EOL;
    }
}