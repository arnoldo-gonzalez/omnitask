<?php
namespace App\Models;

use App\Models\MysqlModel;

class UsersModel extends MysqlModel {
    protected static $table = "users";

    public static function create_user(array $data) {
        $another_account = parent::find_one("email = '{$data["email"]}'", "id");
        if (isset($another_account)) {
            return ["code" => "error", "message" => "El email ya esta en uso"];
        }

        $query = "INSERT INTO users (name, email, user_password, premium, pay_method)
                  VALUES ('{$data["name"]}', '{$data["email"]}', '{$data["password"]}', '{$data["premium"]}', '{$data["pay_method"]}')";

        $code = parent::execute($query, false);
        $result = ["code" => $code];
        $result["id"] = self::find_one("email = '{$data["email"]}' and user_password = '{$data["password"]}'", "id")["id"];

        return $result;
    }

    public static function delete(string $where_stament) {
        $query = "DELETE FROM". static::$table ."WHERE $where_stament";
        $code = parent::execute($query, false);
        $result = ["code" => $code];
        return $result;
    }

    public static function update(string $id, string $changes, array $data) {
        if (in_array("email", $data)) {
            $another_account = parent::find_one("email = '{$data["email"]}'", "id");
            if (isset($another_account)) return ["code" => "error", "message" => "El email ya esta en uso"];
        }

        $query = "UPDATE users SET $changes WHERE id = $id";
        $code = parent::execute($query, false);
        $result = ["code" => $code];
        return $result;
    }
}
