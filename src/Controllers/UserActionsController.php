<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Interfaces\UserActionsInterface;
use App\Traits\UserActionsTraits;
use App\Models\UsersModel;
use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

class UserActionsController implements UserActionsInterface {
    use UserActionsTraits;
    private static $aviable_changable_fields = ["email", "name", "password", "premium"];

    private function check_body_data(Request $req, Response $res, array $jwt) {
        $body = $req->getParsedBody();
        if (!isset($body) || !isset($body["email"]) || !isset($body["password"])) {
            $new_res = self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Not valid data"]]);
            return ["ok" => false, "new_res" => $new_res];
        }
        if (!ValHelper::email($body["email"]) || !ValHelper::password($body["password"])) {
            $new_res = self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Not valid data"]]);
            return ["ok" => false, "new_res" => $new_res];
        }

        $account = UsersModel::find_one("email = '{$body["email"]}' and id = '{$jwt["id"]}'");
        if (!isset($account) || !password_verify($body["password"], $account["user_password"])) {
            $new_res = self::return_error_json($res, ["ok" => false, "errors" => ["Not valid data"]]);
            return ["ok" => false, "new_res" => $new_res];
        }
        
        return ["ok" => true];
    }

    public function delete_user(Request $req, Response $res, array $args) {
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $is_valid_body = self::check_body_data($req, $res, $jwt);
        if (!$is_valid_body["ok"]) return $is_valid_body["new_res"];

        $result = UsersModel::delete("email = '{$body["email"]}' and id = '{$jwt["id"]}'");
        if ($result["code"] !== "00000")
            return self::return_error_json($res, ["ok" => false, "errors" => ["Somethig went wrong, please, try again later"]]);

        $payload = json_encode(["ok" => true, "next_url" => "/"]);
        $res->getBody()->write($payload);
        return $res->withHeader("Content-Type", "application/json");
    }

    public function change_user_data(Request $req, Response $res, array $args) {
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $is_valid_body = self::check_body_data($req, $res, $jwt);
        if (!$is_valid_body["ok"]) return $is_valid_body["new_res"];

        $changes = $req->getParsedBody()["changes"];
        if (!isset($changes)) return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Not enough data"]]);

        $changes_str = "";
        $obtain_val = fn($field, $new_val) => ValHelper::$field($new_val);
        foreach($changes as $field => $new_val) {
            if (!in_array($field, self::$aviable_changable_fields) || !$obtain_val($field, $new_val))
                return self::return_error_json($res, ["ok" => false, "code" => 400, "errors" => ["Not valid data"]]);
            if ($field === "password") {
                $field = "user_password";
                $new_val = password_hash($new_val, PASSWORD_BCRYPT);
            }
            $changes_str .= "$field = '$new_val', ";
        }

        $changes_str = substr($changes_str, 0, -2);
        $result = UsersModel::update($jwt["id"], $changes_str, $changes);
        if ($result["code"] !== "00000") {
            $error_message = $result["message"] ? $result["message"] : "Somethig went wrong, please, try again later";
            return self::return_error_json($res, ["ok" => false, "errors" => $error_message]);
        }

        $res->getBody()->write( json_encode(["ok" => true]) );
        return $res->withHeader("Content-Type", "application/json");
    }

    public function get_data(Request $req, Response $res, array $args) {
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $account = UsersModel::find_one("id = '{$jwt["id"]}'", "id, name, email, premium, pay_method");
        $payload = json_encode($account);
        $res->getBody()->write($payload);

        return $res->withHeader("Content-Type", "application/json");
    }

    public function is_logged(Request $req, Response $res, array $args) {
        $jwt = ValHelper::is_logged($req);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $payload = json_encode(["ok" => "true"]);
        $res->getBody()->write($payload);
        return $res->withHeader("Content-Type", "application/json");
    }
}
