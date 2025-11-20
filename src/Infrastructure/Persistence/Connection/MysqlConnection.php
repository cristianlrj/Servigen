<?php
namespace Infrastructure\Persistence\Connection;

use PDO;

class MysqlConnection {
    public static function get(): PDO {
        return new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }
}