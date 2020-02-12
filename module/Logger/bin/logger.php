<?php
namespace Logger;

use Exception;
use Logger\Model\Entry;
use Logger\Model\Nmea\Gga;
use Logger\Model\Nmea\Vtg;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = fopen('/dev/ttyACM0', 'r');
stream_set_blocking($stream, true);

while (true) {
    try {
        $data = trim(fgets($stream));

        if (preg_match('#^\$GN(GGA|VTG)#', $data)) {
            $entry = Entry::createFromNMEA($data);

            if ($entry instanceof Gga) {
                printf(
                    'GGA SATS: %d ALT: %d GEOS: %.02f HDOP: %.02f' . PHP_EOL,
                    $entry->sat,
                    $entry->alt,
                    $entry->geos,
                    $entry->hdop
                );
            }

            if ($entry instanceof Vtg) {
                printf(
                    'VTG Course True: %.02f Course Mag: %.02f SPD Nautic: %.02f SPD Metric: %.02f' . PHP_EOL,
                    $entry->course_t,
                    $entry->course_m,
                    $entry->speed_n,
                    $entry->speed_m
                );
            }
        }
    }
    catch (Exception $e) {
        print $e->getMessage() . PHP_EOL;
    }
}