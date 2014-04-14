<?php
use Doctrine\DBAL\Schema\Table;

$schema = $app['dbs']['cambuse']->getSchemaManager();

if (!$schema->tablesExist('orders')) {
    
    $orders = new Table('orders');
    $orders->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $orders->setPrimaryKey(array('id'));
    $orders->addColumn('total', 'decimal', array('precision' => 7, 'scale' => 2));
    $orders->addColumn('note', 'text');
    $orders->addColumn('datetime', 'datetime', ['notNull' => false]);
    $orders->addColumn('status', 'integer', ['default' => 0]);
    $orders->addColumn('user_id', 'integer');
    $orders->addColumn('accept', 'integer');
    $orders->addColumn('shop', 'string', array('length' => 20));
    
    $orders->addColumn('name', 'string', array('length' => 255));
    $orders->addColumn('surname', 'string', array('length' => 255));
    $orders->addColumn('email', 'string', array('length' => 255));
    $orders->addColumn('phone', 'string', array('length' => 255));
    $orders->addColumn('scout_group', 'string', array('length' => 255));
    $orders->addColumn('scout_unit', 'string', array('length' => 255));

    $schema->createTable($orders);
}

if (!$schema->tablesExist('order_products')) {
    $products = new Table('order_products');
    $products->addColumn('order_product_id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $products->setPrimaryKey(array('order_product_id'));
    $products->addColumn('code', 'string', array('length' => 32));
    $products->addColumn('name', 'string', array('length' => 255));
    $products->addColumn('price', 'decimal', array('precision' => 7, 'scale' => 2));
    $products->addColumn('price_regular', 'decimal', array('precision' => 7, 'scale' => 2));
    $products->addColumn('pieces_in_package', 'integer');
    $products->addColumn('id', 'integer');
    $products->addColumn('bio', 'integer');
    $products->addColumn('category_id', 'integer');
    $products->addColumn('quantity', 'integer');
    $products->addColumn('total', 'decimal', array('precision' => 7, 'scale' => 2));
    $products->addColumn('user_id', 'integer');
    $products->addColumn('order_id', 'integer');

    $schema->createTable($products);
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

if (!$schema->tablesExist('markets')) {
    $markets = new Table('markets');
    $markets->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $markets->setPrimaryKey(array('id'));
    $markets->addColumn('name', 'string', array('length' => 255));
    $markets->addUniqueIndex(array('name'));
    $markets->addColumn('description', 'text');
    $markets->addColumn('city', 'string', array('length' => 128));
    $markets->addColumn('address', 'string', array('length' => 255));
    $markets->addColumn('phone', 'string', array('length' => 20));
    $markets->addColumn('mobile', 'string', array('length' => 20));
    $markets->addColumn('email', 'string', array('length' => 128));
    $markets->addColumn('link', 'string', array('length' => 128));
    $markets->addColumn('site', 'string', array('length' => 128));
    
    $markets->addColumn('active', 'integer');

    $schema->createTable($markets);

    $app['dbs']['cambuse']->insert('markets', array(
      'name' => 'azienda agricola Pippo di paolino paperino',
      'city' => "Rimini",
      'address' => "via le dita dal naso, 10",
      'description' => "descrizione descrizione descrizione descrizione ",
      'phone' => "05411231323",
      'mobile' => "33333333333",
      'email' => "pippo@paolino.it",
      'link' => "",
      'site' => "http://www.paolino.it",
      'active' => 1,
    ));
    $app['dbs']['cambuse']->insert('markets', array(
      'name' => 'azienda apicultrice Pluto di mickey mouse',
      'city' => "san marino",
      'address' => "via le dita dal sedere, 10",
      'description' => "descrizione descrizione descrizione descrizione ",
      'phone' => "05491231231",
      'mobile' => "22222222222",
      'email' => "pluto@topolino.it",
      'link' => "",
      'site' => "http://www.topolino.it",
      'active' => 1,
    ));
}