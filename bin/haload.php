<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Jalle19\Haload\Haload;
use Jalle19\SingleCommandApplication\SingleCommandApplication;

$application = new SingleCommandApplication(Haload::COMMAND_NAME, Haload::class);
$application->run();
//use Icicle\Http\Server\Server;
//use Icicle\Loop;
//use Jalle19\Haload\Http\Router;
//
//$server = new Server(new Router());
//$server->listen(9712, '::');
//
//Loop\run();
