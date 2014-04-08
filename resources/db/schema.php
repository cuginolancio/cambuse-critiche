<?php
use Doctrine\DBAL\Schema\Table;

$schema = $app['dbs']['cambuse']->getSchemaManager();

if (!$schema->tablesExist('users')) {
    $users = new Table('users');
    $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $users->setPrimaryKey(array('id'));
    $users->addColumn('username', 'string', array('length' => 32));
    $users->addUniqueIndex(array('username'));
    $users->addColumn('password', 'string', array('length' => 255));
    $users->addColumn('roles', 'string', array('length' => 255));
    $users->addColumn('salt', 'string', array('length' => 255));
    $users->addColumn('name', 'string', array('length' => 255));
    $users->addColumn('surname', 'string', array('length' => 255));
    $users->addColumn('email', 'string', array('length' => 255));
    $users->addColumn('phone', 'string', array('length' => 255));
    $users->addColumn('scout_group', 'string', array('length' => 255));
    $schema->createTable($users);

    $encoder = $app['security.encoder_factory']->getEncoder('Lancio\Cambuse\Entity\User');

    $salt = "";
    $password = $encoder->encodePassword('lancio', $salt);
    
    $app['dbs']['cambuse']->insert('users', array(
      'username' => 'lancio',
      'password' => $password,
      'salt' => $salt,
      'roles' => 'ROLE_USER',
      'name' => 'Luca',
      'surname' => 'Lancioni',
      'email' => 'cuginolancio@gmail.com',
      'phone' => '3288296719',
      'scout_group' => 'Rimini 2',
      
      
    ));
    $salt = "";
    $password = $encoder->encodePassword('admin', $salt);
    $app['dbs']['cambuse']->insert('users', array(
      'username' => 'admin',
      'password' => $password,
      'salt' => $salt,
      'roles' => 'ROLE_ADMIN',
      'name' => 'Imma',
      'surname' => 'Lovicu',
      'email' => 'cuginolancio@facebook.com',
      'phone' => '3286977355',
      'scout_group' => 'Oliena 1',
    ));
}

if (!$schema->tablesExist('products')) {
    $products = new Table('products');
    $products->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $products->setPrimaryKey(array('id'));
    $products->addColumn('code', 'string', array('length' => 32));
    $products->addUniqueIndex(array('code'));
    $products->addColumn('name', 'string', array('length' => 255));
    $products->addColumn('price', 'decimal', array('precision' => 7, 'scale' => 2));
    $products->addColumn('price_regular', 'decimal', array('precision' => 7, 'scale' => 2));
    $products->addColumn('pieces_in_package', 'integer');
    $products->addColumn('bio', 'integer');
    $products->addColumn('category_id', 'integer');
    $products->addColumn('active', 'integer');

    $schema->createTable($products);

    $app['dbs']['cambuse']->insert('products', array(
      'code' => '001',
      'name' => 'Caffè',
      'price' => 9,
      'price_regular '=> 10,
      'pieces_in_package' => 1,
      'bio' => 0,
      'category_id' => 1,
      'active' => 1,
    ));
    $app['dbs']['cambuse']->insert('products', array(
      'code' => '002',
      'name' => 'Caffè bio',
      'price' => 11,
      'price_regular' => 12,
      'pieces_in_package' => 1,
      'bio' => 1,
      'category_id' => 1,
      'active' => 1,
    ));
    $app['dbs']['cambuse']->insert('products', array(
      'code' => '003',
      'name' => 'Biscotti',
      'price' => 5,
      'price_regular' => 6,
      'pieces_in_package' => 4,
      'bio' => 0,
      'category_id' => 2,
      'active' => 1,
    ));
    $app['dbs']['cambuse']->insert('products', array(
      'code' => '004',
      'name' => 'Pasta',
      'price' => 20,
      'price_regular' => 24,
      'pieces_in_package' => 1,
      'bio' => 1,
      'category_id' => 3,
      'active' => 1,
    ));
    $app['dbs']['cambuse']->insert('products', array(
      'code' => '005',
      'name' => 'Pasta Bio',
      'price' => 25,
      'price_regular' => 30,
      'pieces_in_package' => 1,
      'bio' => 1,
      'category_id' => 3,
      'active' => 1,
    ));
}

if (!$schema->tablesExist('categories')) {
    $categories = new Table('categories');
    $categories->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $categories->setPrimaryKey(array('id'));
    $categories->addColumn('name', 'string', array('length' => 255));
    $categories->addColumn('active', 'integer');

    $schema->createTable($categories);

    $app['dbs']['cambuse']->insert('categories', array(
      'name' => 'Colazione e merenda',
      'active' => 1,
    ));

    $app['dbs']['cambuse']->insert('categories', array(
      'name' => 'Pranzo e cena',
      'active' => 1,
    ));
    
    $app['dbs']['cambuse']->insert('categories', array(
      'name' => 'Prodotti per la pulizia',
      'active' => 1,
    ));
}

/**
 * tabelle joomla 
CREATE TABLE `j25_user_usergroup_map` (
  `user_id` int(10)  NOT NULL DEFAULT '0' ,
  `group_id` int(10)  NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`group_id`)
) ;
    
INSERT INTO `j25_user_usergroup_map` (`user_id`, `group_id`)
VALUES(355,8);


    
CREATE TABLE `j25_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `lastResetTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `resetCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);
    

INSERT INTO `j25_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`, `lastResetTime`, `resetCount`)
VALUES    
    (355,'luca lancioni','cuginolancio','cuginolancio@gmail.com','98d7fc72b4ac58b1df9188ca452369fa:f7Wpu61q3z94rbDB','Super Administrator',0,0,'2008-10-25 12:47:50','2012-11-22 20:55:55','','{\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\",\"admin_style\":\"\"}','0000-00-00 00:00:00',0);
      
CREATE TABLE `j25_comprofiler` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `message_last_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message_number_sent` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `avatarapproved` tinyint(4) NOT NULL DEFAULT '1',
  `approved` tinyint(4) NOT NULL DEFAULT '1',
  `confirmed` tinyint(4) NOT NULL DEFAULT '1',
  `lastupdatedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `registeripaddr` varchar(50) NOT NULL DEFAULT '',
  `cbactivation` varchar(50) NOT NULL DEFAULT '',
  `banned` tinyint(4) NOT NULL DEFAULT '0',
  `banneddate` datetime DEFAULT NULL,
  `unbanneddate` datetime DEFAULT NULL,
  `bannedby` int(11) DEFAULT NULL,
  `unbannedby` int(11) DEFAULT NULL,
  `bannedreason` mediumtext,
  `acceptedterms` tinyint(1) NOT NULL DEFAULT '0',
  `cb_SKYPECBA` varchar(255) DEFAULT NULL,
  `cb_MSNCB` varchar(255) DEFAULT NULL,
  `cb_homepagegruppo` varchar(255) DEFAULT NULL,
  `cb_servizioin` mediumtext,
  `cb_telfisso` varchar(255) DEFAULT NULL,
  `cb_telcell` varchar(255) DEFAULT NULL,
  `cb_gruppo` varchar(255) DEFAULT NULL,
  `cb_indirizzo` varchar(255) DEFAULT NULL,
  `cb_unita` varchar(255) DEFAULT NULL,
  `cb_pseudonimo` varchar(255) DEFAULT NULL,
  `cb_totem` varchar(255) DEFAULT NULL,
  `cb_ricevieventi` tinyint(3)  DEFAULT NULL,
  `cb_perchetiiscrivi` mediumtext,
  `cb_lc` tinyint(3)  DEFAULT NULL,
  `cb_eg` tinyint(3)  DEFAULT NULL,
  `cb_rs` tinyint(3)  DEFAULT NULL,
  `cb_otheremail` varchar(255) DEFAULT NULL,
  `cb_formazione` varchar(255) DEFAULT NULL,
  `cb_deleteme` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`)
) ;
  
INSERT INTO `j25_comprofiler` (`id`, `user_id`, `firstname`, `middlename`, `lastname`, `hits`, `message_last_sent`, `message_number_sent`, `avatar`, `avatarapproved`, `approved`, `confirmed`, `lastupdatedate`, `registeripaddr`, `cbactivation`, `banned`, `banneddate`, `unbanneddate`, `bannedby`, `unbannedby`, `bannedreason`, `acceptedterms`, `cb_SKYPECBA`, `cb_MSNCB`, `cb_homepagegruppo`, `cb_servizioin`, `cb_telfisso`, `cb_telcell`, `cb_gruppo`, `cb_indirizzo`, `cb_unita`, `cb_pseudonimo`, `cb_totem`, `cb_ricevieventi`, `cb_perchetiiscrivi`, `cb_lc`, `cb_eg`, `cb_rs`, `cb_otheremail`, `cb_formazione`, `cb_deleteme`)
VALUES
(355,355,'luca','','lancioni',9,'0000-00-00 00:00:00',0,'355_4902fe919031e.jpg',1,1,1,'2012-10-01 18:03:59','93.149.84.155','',0,NULL,NULL,NULL,NULL,NULL,0,'','','','Non in servizio','','3288296719','Rimini 2','via lucinico 24','','','Albatros Capace',1,NULL,0,0,0,'','3: CFM','');
*/        