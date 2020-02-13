<?php
namespace Logger;

use Application\Util\Coordinates;

require_once __DIR__ . '/../../../vendor/autoload.php';

$gps = '4715.43783';
// $gps = '00836.43783';
print Coordinates::gpsToDec($gps, 'N');