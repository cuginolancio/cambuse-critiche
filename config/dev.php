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
        'path'     => __DIR__.'/../data/app_dev.db',  
//        'driver'    => 'pdo_sqlite',
//        'host'      => 'mysql_write.someplace.tld',
//        'dbname'    => 'my_database',
//        'user'      => 'my_username',
//        'password'  => 'my_password',
//        'charset'   => 'utf8',
    ],
];

$mailer = $app['swiftmailer.options'];
//$mailer['disable_delivery'] = true;
$mailer['delivery_address'] = "cuginolancio@gmail.com";
$app['swiftmailer.options'] = $mailer;


$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/silex_dev.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => $app['cache.path'] . '/profiler',
    'intercept_redirects' => true,
));
$app->mount('/_profiler', $p);