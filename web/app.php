<?php

ini_set('display_errors', 0);

require __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application(["debug" => false, "env" => 'prod'] );

Request::enableHttpMethodParameterOverride();

$app = require __DIR__ . "/../src/app.php";

require __DIR__ . "/../config/{$app['env']}.php";

require __DIR__ . "/../resources/db/schemaJoomla.php";
require __DIR__ . "/../resources/db/schemaCambuse.php";

require __DIR__ . "/../src/shares.php";
require __DIR__ . "/../src/routes.php";

$app->run();
