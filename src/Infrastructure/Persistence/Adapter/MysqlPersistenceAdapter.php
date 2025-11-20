<?php
namespace Infrastructure\Persistence\Adapter;

use Infrastructure\Persistence\Connection\MysqlConnection;

use PDO;

abstract class MysqlPersistenceAdapter {
    protected PDO $conn;

    public function __construct() {
        $this->conn = MysqlConnection::get();
    }

}
