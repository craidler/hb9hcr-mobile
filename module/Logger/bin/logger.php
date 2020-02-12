<?php
namespace Logger;

# todo: this script could use some love

use DateTime;
use Nykopol\GpsdClient\Client as GpsdClient;

$i = 0;
$dir = __DIR__ . '/../../../public/data/logger';
$fnl = sprintf('%s/%d-gps.dat', $dir, date('Ymd'));
$fnc = sprintf('%s/gps.dat', $dir);

if (!file_exists($fnl)) file_put_contents($fnl, 'utc:position:alt:speed:sats' . PHP_EOL);

$fhl = fopen($fnl, 'a');

$ch = new GpsdClient();
$ch->connect();
$ch->watch();

$sat = 0;

while (true) {
    $i = $i >= 60 ? 0 : $i + 1;
    $sky = $ch->getNext('SKY', false, false);

    if ($sky) {
        $sky = json_decode($sky, JSON_OBJECT_AS_ARRAY);
        if (array_key_exists('satellites', $sky)) $sat = count($sky['satellites']);
    }

    $tpv = json_decode($ch->getNext('TPV'), JSON_OBJECT_AS_ARRAY);

    $data = sprintf('%d:%f,%f:%d:%d:%d',
        DateTime::createFromFormat(DATE_RFC3339_EXTENDED, $tpv['time'])->getTimestamp(),
        $tpv['lat'],
        $tpv['lon'],
        $tpv['alt'],
        $tpv['speed'],
        $sat
    );

    file_put_contents($fnc, $data);

    if (0 == $i % 15) {
        fwrite($fhl, $data . PHP_EOL);
    }
}

fclose($fhl);
$ch->disconnect();