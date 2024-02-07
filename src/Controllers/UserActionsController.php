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

    public function delete_user(Request $req, Response $res, array $args) {
        $res->getBody()->write("Not implemented yet");
        return $res;
    }

    public function change_user_data(Request $req, Response $res, array $args) {
        $res->getBody()->write("Not implemented yet");
        return $res;
    }

    public function get_data(Request $req, Response $res, array $args) {
        $req_jwt = ValHelper::is_logged($req);
        if (!isset($req_jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $jwt = JWTHelper::decode_jwt($req_jwt);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $account = UsersModel::find_one("id = '{$jwt["id"]}'", "id, name, email, premium, pay_method");
        $payload = json_encode($account);
        $res->getBody()->write($payload);

        return $res
            ->withHeader("Content-Type", "application/json");
    }

    public function is_logged(Request $req, Response $res, array $args) {
        $req_jwt = ValHelper::is_logged($req);
        if (!isset($req_jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $jwt = JWTHelper::decode_jwt($req_jwt);
        if (!isset($jwt)) return self::return_error_json($res, ["ok" => false, "code" => 401, "errors" => ["Is not logged"]]);

        $payload = json_encode(["ok" => "true"]);
        $res->getBody()->write($payload);
        return $res->withHeader("Content-Type", "application/json");
    }
}
