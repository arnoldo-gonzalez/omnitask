<?php
namespace App\Models;

use PDO;

class MysqlModel {
    private static function iterableResult($stament) {
        $rows = [];
        while ($r = $stament->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $r;
        }

        return $rows;
    }

    protected static function execute(string $query, bool $return_array) {
        $connection = new PDO("mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stament = $connection->prepare($query);
        $results = $stament->execute();

        return $return_array ? self::iterableResult($stament) : $stament->errorCode();
    }
}
