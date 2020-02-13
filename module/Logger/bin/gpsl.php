<?php
namespace Logger;

use Laminas\Config\Config;
use Logger\Service\GpsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require_once __DIR__ . '/../../../vendor/autoload.php';

$config = (new Config(include_once __DIR__ . '/../config/module.config.php'))->get(Module::class);
$server = IoServer::factory(new HttpServer(new WsServer(new GpsServer($config))), $config->get('port', 8888));
$server->run();