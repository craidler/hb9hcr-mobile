<?php


$fh = fopen(__DIR__ . '/../../../public/data/logger/' . date('Ymd') . '-gps.dat', 'a');

while (true) {
    fwrite($fh, sprintf('%d:%f,%f:%d:%d:%d' . PHP_EOL,
        time(),
        rand(-90, 90),
        rand(-90, 90),
        rand(0, 2000),
        rand(0, 120),
        rand(0, 30)
    ));

    sleep(15);
}

fclose($fh);