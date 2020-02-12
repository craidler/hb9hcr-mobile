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
            $data[0] = $match[1];
            $entry = Entry::createFromNMEA($data);
            var_dump($entry->altitude);
        }
    }
    catch (Exception $e) {
        print $e->getMessage() . PHP_EOL;
    }
}