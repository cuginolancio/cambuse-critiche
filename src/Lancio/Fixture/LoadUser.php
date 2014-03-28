<?php

namespace Lancio\Fixture;

use Doctrine\DBAL\Schema\Table;
    
class LoadUser {

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    
    public function load()
    {
        $schema = $this->conn->getSchemaManager();
        if (!$schema->tablesExist('users')) {
            $users = new Table('users');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addUniqueIndex(array('username'));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('roles', 'string', array('length' => 255));

            $schema->createTable($users);

            $this->conn->insert('users', array(
              'username' => 'fabien',
              'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
              'roles' => 'ROLE_USER'
            ));

            $this->conn->insert('users', array(
              'username' => 'admin',
              'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
              'roles' => 'ROLE_ADMIN'
            ));
        }
    }
}
