<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Interfaces\SubtasksInterface;
use App\Traits\UserActionsTraits;
use App\Models\TasksModel;
use App\Models\SubtasksModel;
use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

class SubtasksController implements SubtasksInterface {
    use UserActionsTraits;

    private function check_subtask_data(array $data) {
        if (!isset($data) || !array_key_exists("title", $data) || !array_key_exists("id_parent_task", $data) || !array_key_exists("datetime_start", $data) || !array_key_exists("datetime_finish", $data)) return null;
        return true;
    }

    public function add_subtask(Request $req, Response $res, array $args){
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["No ha ingresado en su cuenta"]]);
        if (!$jwt["premium"]) 
            return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Para usar esta funcion se necesita ser usuario premium"]]);

        $data = $req->getParsedBody();
        $is_valid_data = self::check_subtask_data($data);
        if (!isset($is_valid_data)) return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos invalidos"]]);
        
        if (!ValHelper::datetime($data["datetime_start"]) || !ValHelper::datetime($data["datetime_finish"]) || !ValHelper::subtitle($data["title"])  || !is_int($data["id_parent_task"]))
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos invalidos"]]);

        $parent_task = TasksModel::find_one("id = {$data["id_parent_task"]}");
        if (!isset($parent_task)) return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos invalidos"]]);
        
        $time_start_diff = strtotime($data["datetime_start"]) - strtotime($parent_task["datetime_start"]);
        $time_finish_diff = strtotime($data["datetime_finish"]) - strtotime($parent_task["datetime_finish"]);
        $time_start_finish_diff = strtotime($data["datetime_finish"]) - strtotime($data["datetime_start"]);

        if ($time_start_diff < 0 || $time_finish_diff > 0 || $time_start_finish_diff < 60)
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos invalidos"]]);

        $result = SubtasksModel::create($data);
        if ($result["code"] !== "00000") {
            $error_message = $result["message"] ? $result["message"] : "Un error inesperado ocurrio, por favor, intentelo mas tarde";
            return self::return_error_json($res, ["ok" => false, "errors" => [$error_message]]);
        }

        $res->getBody()->write(json_encode(["ok" => true, "id_subtask" => $result["id"]]));
        return $res->withHeader("Content-Type", "application/json");
    }

    public function delete_subtask(Request $req, Response $res, array $args){
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["No ha ingresado en su cuenta"]]);
        if (!$jwt["premium"]) 
            return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Para usar esta funcion se necesita ser usuario premium"]]);

        $data = $req->getParsedBody();
        if (!isset($data) || !array_key_exists("id_parent_task", $data) || !array_key_exists("id_subtask", $data))
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos invalidos"]]);

        $result = SubtasksModel::delete("id = {$data["id_subtask"]} and fktask = {$data["id_parent_task"]}");
        if ($result["code"] !== "00000") {
            $error_message = $result["message"] ? $result["message"] : "Un error inesperado ocurrio, por favor, intentelo mas tarde";
            return self::return_error_json($res, ["ok" => false, "errors" => [$error_message]]);
        }

        $res->getBody()->write(json_encode(["ok" => true]));
        return $res->withHeader("Content-Type", "application/json");
    }
}
