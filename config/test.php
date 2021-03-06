<?php

require __DIR__ . "/prod.php";

$app['debug'] = true;

//$app['db.options'] = array(
//    'driver'   => 'pdo_sqlite',
//    'path'     => __DIR__.'/../../data/app_test.db',    
//);

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
        'path'     => __DIR__.'/../data/app_test.db',  
//        'driver'    => 'pdo_sqlite',
//        'host'      => 'mysql_write.someplace.tld',
//        'dbname'    => 'my_database',
//        'user'      => 'my_username',
//        'password'  => 'my_password',
//        'charset'   => 'utf8',
    ],
];

$mailer = $app['swiftmailer.options'];
$mailer['disable_delivery'] = true;
//$mailer['delivery_address'] = "cuginolancio@gmail.com";
$app['swiftmailer.options'] = $mailer;

$app['session.test'] = true;
        

//$app['security.users'] = array('username' => array('ROLE_USER', 'password'));
