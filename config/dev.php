<?php
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

require __DIR__ . "/prod.php";

$app['debug'] = true;

$app['dbs.options'] =  [
    'j' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/joomla.db',  
//        'driver'    => 'pdo_mysql',
//        'host'      => 'mysql_read.someplace.tld',
//        'dbname'    => 'my_database',
//        'user'      => 'my_username',
//        'password'  => 'my_password',
//        'charset'   => 'utf8',
    ],
    'cambuse' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/app.db',  
//        'driver'    => 'pdo_sqlite',
//        'host'      => 'mysql_write.someplace.tld',
//        'dbname'    => 'my_database',
//        'user'      => 'my_username',
//        'password'  => 'my_password',
//        'charset'   => 'utf8',
    ],
];

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/silex_dev.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => $app['cache.path'] . '/profiler',
));
$app->mount('/_profiler', $p);