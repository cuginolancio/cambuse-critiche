<?php

// Doctrine
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

$app['swiftmailer.options'] = array(
    'transport' => 'gmail',
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'security' => 'ssl',
    'encryption' => 'ssl',
    'username' => GMAIL_USR,
    'password' => GMAIL_PWD,
);

$app['shops'] = [
    'pachamama' => [
        'name' => 'Pachamama',
        'email' => 'luca@lanc.io',
    ]
];

