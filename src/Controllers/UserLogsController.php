<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Interfaces\UserLogsInterface;
use App\Traits\UserLogsTraits;
use App\Traits\UserActionsTraits;
use App\Models\UsersModel;
use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

class UserLogsController implements UserLogsInterface {
    use UserLogsTraits, UserActionsTraits;

    public function sign_in(Request $req, Response $res, array $args) {
        $body = $req->getParsedBody();
        if (!isset($body) || !is_array($body) ) return self::return_error_json($res, ["No se suministraron datos"]);

        $errors = ValHelper::user_signin($body);       
        if (!$errors["ok"]) return self::return_error_json($res, $errors["errors"]);

        $account = UsersModel::find_one("email = '{$body["email"]}'");
        if (!isset($account) || !password_verify($body["password"], $account["user_password"])) {
            return self::return_error_json($res, ["El email o la contraseña son incorrectos"]);
        }

        $jwt = JWTHelper::create_jwt(["id" => $account["id"], "email" => $body["email"]]);
        $data_to_send = [
            "ok" => true, "token" => $jwt, 
            "name" => $account["name"], "id" => $account["id"], 
            "next_url" => "/user/{$account["id"]}/tasks"
        ];

        $payload = json_encode($data_to_send);
        $res->getBody()->write($payload);

        return $res
            ->withHeader('Content-Type', 'application/json');
    }


    public function sign_up(Request $req, Response $res, array $args) {
        $body = $req->getParsedBody();
        if (!isset($body) || !is_array($body) ) return self::return_error_json($res, ["No se suministraron datos"]);

        $errors = ValHelper::user_signup($body);
        if (!$errors["ok"]) return self::return_error_json($res, $errors["errors"]);

        $body["password"] = password_hash($body["password"], PASSWORD_BCRYPT);
        $body["pay_method"] = $body["premium"] === "true" ? $body["pay_method"] : null;
        $body["premium"] = $body["premium"] === "true" ? 1 : 0;
        
        $result = UsersModel::create_user($body);

        if ($result["code"] !== "00000") {
            $error_message = $result["message"] ? $result["message"] : "Somethig went wrong, please, try again later";
            return self::return_error_json($res, [$error_message]);
        }

        $jwt = JWTHelper::create_jwt(["id" => $result["id"], "email" => $body["email"]]);
        $data_to_send = [
            "ok" => true, "token" => $jwt, 
            "name" => $body["name"], "id" => $result["id"], 
            "next_url" => "/user/{$result["id"]}/tasks"
        ];

        $payload = json_encode($data_to_send);
        $res->getBody()->write($payload);

        return $res
            ->withHeader('Content-Type', 'application/json');
    }
}




