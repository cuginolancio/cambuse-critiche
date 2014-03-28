<?php
use Doctrine\DBAL\Schema\Table;

$schema = $app['db']->getSchemaManager();

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
    
    $app['db']->insert('users', array(
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
    $app['db']->insert('users', array(
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

    $app['db']->insert('products', array(
      'code' => '001',
      'name' => 'Caffè',
      'price' => 9,
      'price_regular '=> 10,
      'pieces_in_package' => 1,
      'bio' => 0,
      'category_id' => 1,
      'active' => 1,
    ));
    $app['db']->insert('products', array(
      'code' => '002',
      'name' => 'Caffè bio',
      'price' => 11,
      'price_regular' => 12,
      'pieces_in_package' => 1,
      'bio' => 1,
      'category_id' => 1,
      'active' => 1,
    ));
    $app['db']->insert('products', array(
      'code' => '003',
      'name' => 'Biscotti',
      'price' => 5,
      'price_regular' => 6,
      'pieces_in_package' => 4,
      'bio' => 0,
      'category_id' => 2,
      'active' => 1,
    ));
    $app['db']->insert('products', array(
      'code' => '004',
      'name' => 'Pasta',
      'price' => 20,
      'price_regular' => 24,
      'pieces_in_package' => 1,
      'bio' => 1,
      'category_id' => 3,
      'active' => 1,
    ));
    $app['db']->insert('products', array(
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

    $app['db']->insert('categories', array(
      'name' => 'Colazione e merenda',
      'active' => 1,
    ));

    $app['db']->insert('categories', array(
      'name' => 'Pranzo e cena',
      'active' => 1,
    ));
    
    $app['db']->insert('categories', array(
      'name' => 'Prodotti per la pulizia',
      'active' => 1,
    ));
}