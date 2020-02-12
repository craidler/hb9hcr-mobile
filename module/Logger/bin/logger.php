<?php
namespace Logger;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = fopen('/dev/ttyACM0', 'r');
stream_set_blocking($stream, true);
while (true) {
    $data = explode(',', trim(fgets($stream)));
    if (!isset($data[0])) continue;
    switch ($data[0]) {
        case '$GNVTG':
            var_dump($data);
            break;
    }
}