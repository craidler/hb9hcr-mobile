<?php
namespace Logger;

require_once __DIR__ . '/../../../vendor/autoload.php';

# todo: this script could use some love

$errno = null;
$errstr = null;
$stream = stream_socket_client('tcp://localhost:2947',$errno ,$errstr , 10);
stream_set_blocking($stream, 1);
fwrite($stream, '?WATCH={"enable":true,"json":true}');
while (true) var_dump(fgets($stream));