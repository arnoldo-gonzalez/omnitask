<?php
namespace App\Models;

use PDO;

class MysqlModel {
    protected static $table = "dual";

    private static function iterableResult($stament) {
        $rows = [];
        while ($r = $stament->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $r;
        }

        return $rows;
    }

    public static function find_one(string $where_stament, string $cols = "*") {
        $query = "SELECT $cols FROM " . static::$table . " WHERE $where_stament";
        $results = self::execute($query, true);
        return $results[0];
    }

    protected static function execute(string $query, bool $return_array) {
        $connection = new PDO("mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stament = $connection->prepare($query);
        $results = $stament->execute();

        return $return_array ? self::iterableResult($stament) : $stament->errorCode();
    }
}
