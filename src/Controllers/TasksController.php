<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Interfaces\TasksInterface;
use App\Traits\UserActionsTraits;
use App\Models\TasksModel;
use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

class TasksController implements TasksInterface {
    use UserActionsTraits;

    private function check_task_data(array $data) {
        if (!isset($data) || !array_key_exists("title", $data) || !array_key_exists("description", $data) || !array_key_exists("datetime_start", $data) || !array_key_exists("datetime_finish", $data)) return null;
        return true;
    }

    public function index (Request $req, Response $res, array $args){
        $view = Twig::fromRequest($req);
        return $view->render($res, "tasks.html", []);
    }

    public function get_tasks(Request $req, Response $res, array $args){
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"], "jwt" => $jwt]);

        $data = TasksModel::fetch($jwt["id"]);
        $payload = json_encode($data);
        $res->getBody()->write($payload);

        return $res->withHeader("Content-Type", "application/json");
    }

    public function add_task(Request $req, Response $res, array $args){
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $data = $req->getParsedBody();
        $is_valid_data = self::check_task_data($data);
        if (!isset($is_valid_data)) return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos Invalidos"]]);

        if (!ValHelper::datetime($data["datetime_start"]) || !ValHelper::datetime($data["datetime_finish"]) || !ValHelper::title($data["title"])  || !ValHelper::description($data["description"]))
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos Invalidos"]]);
        
        $time_start_finish_diff = strtotime($data["datetime_finish"]) - strtotime($data["datetime_start"]);
        if ($time_start_finish_diff < 60) 
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Datos Invalidos"]]);

        $result = TasksModel::create($data, $jwt["id"]);
        if ($result["code"] !== "00000") {
            $error_message = $result["message"] ? $result["message"] : "Somethig went wrong, please, try again later";
            return self::return_error_json($res, ["ok" => false, "errors" => [$error_message]]);
        }

        $res->getBody()->write(json_encode(["ok" => true, "id_task" => $result["id"]]));
        return $res->withHeader("Content-Type", "application/json");
    }

    public function delete_task(Request $req, Response $res, array $args){
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $body = $req->getParsedBody();
        if (!isset($body) || !array_key_exists("id", $body) || !is_int($body["id"]))
            return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Not valid data"]]);

        $already_task = TasksModel::find_one("id = '{$body["id"]}' and fkuser = '{$jwt["id"]}'");
        if (!isset($already_task))
            return self::return_error_json($res, ["ok" => false, "errors" => ["Not valid data"]]);

        $result = TasksModel::delete("id = '{$body["id"]}' and fkuser = '{$jwt["id"]}'");
        if ($result["code"] !== "00000")
            return self::return_error_json($res, ["ok" => false, "errors" => ["Somethig went wrong, please, try again later"]]);

        $payload = json_encode(["ok" => true]);
        $res->getBody()->write($payload);
        return $res->withHeader("Content-Type", "application/json");
    }
}
