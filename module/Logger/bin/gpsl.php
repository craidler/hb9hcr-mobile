<?php
namespace Logger;

use Application\Util\Coordinates;

require_once __DIR__ . '/../../../vendor/autoload.php';

$gps = '04713.12346';
print Coordinates::gpsToDec($gps, 'N');