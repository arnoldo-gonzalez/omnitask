<?php
namespace App\Models;

use App\Models\MysqlModel;

class SubtasksModel extends MysqlModel {
    protected static $table = "subtasks";

    public static function create(array $data){
        $fktask = $data["id_parent_task"];
        $another_subtask = parent::find_one("datetime_start = '{$data["datetime_start"]}' and fktask = $fktask");
        if (isset($another_task)) {
            return ["code" => "error", "message" => "Hay otra subtarea con esa fecha de inicio"];
        }

        $query = "INSERT INTO " . static::$table . " (title, datetime_start, datetime_finish, fktask) 
            VALUES ('${data["title"]}', '${data["datetime_start"]}', '${data["datetime_finish"]}', $fktask)";

        $code = parent::execute($query, false);
        $results = ["code" => $code];
        $results["id"] = self::find_one("datetime_start = '{$data["datetime_start"]}' and fktask = $fktask", "id")["id"];
        return $results;
    }

    public static function delete(string $where_stament) {
        $query = "DELETE FROM ". static::$table ." WHERE $where_stament";
        $code = parent::execute($query, false);
        $result = ["code" => $code];
        return $result;
    }
}
