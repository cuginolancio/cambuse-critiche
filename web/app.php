<?php

require __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application(["debug" => false, "env" => 'prod'] );

Request::enableHttpMethodParameterOverride();

$app = require __DIR__ . "/../src/app.php";

$app->run();