<?php

use Doctrine\DBAL\Schema\Table;

$prefix = "j25_";
$schema = $app['dbs']['j']->getSchemaManager();

if (!$schema->tablesExist($prefix . 'users')) {
    $users = new Table($prefix . 'users');
    $users->addColumn('id', 'integer', array('unsigned' => true));
    $users->setPrimaryKey(array('id'));
    $users->addColumn('email', 'string', array('length' => 255));
    $users->addColumn('username', 'string', array('length' => 150));
    $users->addUniqueIndex(array('username'));
    $users->addColumn('password', 'string', array('length' => 100));
    $users->addColumn('name', 'string', array('length' => 255));
    $users->addColumn('usertype', 'string', array('length' => 25));
    $users->addColumn('block', 'integer', array('default' => 0 ));
    $users->addColumn('sendEmail', 'integer', array('default' => 0 ));
    $users->addColumn('registerDate', 'datetime', array('default' => '0000-00-00 00:00:00'));
    $users->addColumn('lastvisitDate', 'datetime', array('default' => '0000-00-00 00:00:00'));
    $users->addColumn('params', 'text');
    $users->addColumn('lastResetTime', 'datetime', array('default' => '0000-00-00 00:00:00'));
    $users->addColumn('resetCount', 'integer', array('default' => 0 ));
    $schema->createTable($users);

    $encoder = $app['security.encoder_factory']->getEncoder('Lancio\Cambuse\Entity\User');

    $salt = "f7Wpu61q3z94rbDB";
    $password = $encoder->encodePassword('lancio', $salt). ':' . $salt;
    
    $app['dbs']['j']->insert($prefix . 'users', array(
      'id' => 355,
      'username' => 'lancio',
      'password' => $password,
      'name' => 'Luca',
      'email' => 'cuginolancio@gmail.com',
      'usertype' => 'Super Administrator',
      'params' => '{\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\",\"admin_style\":\"\"}',
    ));
}

if (!$schema->tablesExist($prefix . 'comprofiler')) {
    $profile = new Table($prefix . 'comprofiler');
    $profile->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $profile->setPrimaryKey(array('id'));
    $profile->addColumn('user_id', 'integer', array('unsigned' => true));
    $profile->addUniqueIndex(array('user_id'));
    $profile->addColumn('firstname', 'string', array('length' => 100));
    $profile->addColumn('middlename', 'string', array('length' => 100));
    $profile->addColumn('lastname', 'string', array('length' => 100));
    $profile->addColumn('cb_gruppo', 'string', array('length' => 100));
    $profile->addColumn('cb_unita', 'string', array('length' => 100));
    $profile->addColumn('cb_telcell', 'string', array('length' => 100));
    
    $schema->createTable($profile);
      
    $app['dbs']['j']->insert($prefix . 'comprofiler', array(
      'user_id' => 355,
      'firstname' => 'luca',
      'middlename' => '',
      'lastname' => "lancio",
      'cb_gruppo' => "Rimini 2",
      'cb_unita' => "Croce del Sud",
      'cb_telcell' => "123123",
    ));
}

if (!$schema->tablesExist($prefix . 'user_usergroup_map')) {
    $usergroup = new Table($prefix . 'user_usergroup_map');
    $usergroup->addColumn('user_id', 'integer', array('unsigned' => true));
    $usergroup->addColumn('group_id', 'integer', array('unsigned' => true));
    $usergroup->setPrimaryKey(array('user_id', 'group_id'));

    $schema->createTable($usergroup);

    $app['dbs']['j']->insert($prefix . 'user_usergroup_map', array(
      'user_id' => 355,
      'group_id' => 8,
    ));

    $app['dbs']['j']->insert($prefix . 'user_usergroup_map', array(
      'user_id' => 355,
      'group_id' => 9,
    ));
}

    
