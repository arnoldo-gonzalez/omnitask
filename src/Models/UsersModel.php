<?php
namespace App\Models;

use App\Models\MysqlModel;

class UsersModel extends MysqlModel {
    public static function create_user(array $data) {
        $another_account = self::find_one("email = '{$data["email"]}'", "id");
        if (isset($another_account)) {
            return ["code" => "error", "message" => "El email ya esta en uso"];
        }

        $query = "INSERT INTO users (name, email, user_password, premium, pay_method)
                  VALUES ('{$data["name"]}', '{$data["email"]}', '{$data["password"]}', '{$data["premium"]}', '{$data["pay_method"]}')";

        $code = parent::execute($query, false);
        $result = [];
        $result["code"] = $code;
        $result["id"] = self::find_one("email = '{$data["email"]}' and user_password = '{$data["password"]}'", "id")["id"];

        return $result;
    }

    public static function find_one(string $where_stament, string $cols = "*") {
        $query = "SELECT $cols FROM users WHERE $where_stament";
        $results = parent::execute($query, true);
        return $results[0];
    }
}
