<?php
namespace App\Models;

use App\Models\MysqlModel;

class TasksModel extends MysqlModel {
    private static function formatResult($results) {
        $prev_id = null;
        $index = -1;
        $formated_results = [];
        foreach($results as $row => $data) {
            if ($prev_id !== $data["parent_id"]) {
                $formated_results[++$index] = [
                    "id" => $data["parent_id"], "title" => $data["parent_title"],
                    "description" => $data["parent_description"], "datetime_start" => $data["parent_start"],
                    "datetime_finish" => $data["parent_finish"], "subtasks" => []
                ];
                $prev_id = $data["parent_id"];
            }

            $formated_results[$index]["subtasks"][] = [
                "id" => $data["child_id"], "title" => $data["child_title"],
                "datetime_start" => $data["child_start"], "datetime_finish" => $data["child_finish"]
            ];
        }

        return $formated_results;
    }

    public static function fetch(int $user_id){
        $query = "SELECT 
                    tasks.id as parent_id, tasks.title as parent_title, tasks.description as parent_description, 
                    tasks.datetime_start as parent_start, tasks.datetime_finish as parent_finish,
                    subtasks.id as child_id, subtasks.title as child_title, subtasks.datetime_start as child_start, 
                    subtasks.datetime_finish as child_finish
                  FROM tasks LEFT JOIN subtasks ON tasks.id = subtasks.fktask 
                  WHERE tasks.fkuser = $user_id 
                  ORDER BY tasks.datetime_start DESC";

        $results = self::execute($query, true);
        $format_results = self::formatResult($results);
        return $format_results;
    }

    public static function add(array $new_task){
        $query = "INSERT INTO tasks (title, description, datetime_start, datetime_finish, fkuser) 
            VALUES (${new_task["title"]}, ${new_task["description"]}, 
                    ${new_task["datetime_start"]}, ${new_task["datetime_finish"]}, ${new_task["fkuser"]})";

        $results = self::execute($query);
        var_dump($results);
    }
}
