<?php

require __DIR__ . '/../../src/Core/Autoload.php';

use Core\Autoload;
use Core\Router;

Autoload::registrate(__DIR__ . '/../../src/');

$router = new Router();
$router->handleRequest();



