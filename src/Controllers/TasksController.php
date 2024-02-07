<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Interfaces\TasksInterface;
use App\Models\TasksModel;
use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

class TasksController implements TasksInterface {
    private function redirect_notlogged(Response $res) {
        $payload = json_encode(["code" => 401, "error" => true]);
        $res->getBody()->write($payload);
        return $res
            ->withHeader('Content-Type', 'application/json');
    }    

    public function index (Request $req, Response $res, array $args){
        $view = Twig::fromRequest($req);
        return $view->render($res, "tasks.html", []);
    }

    public function get_tasks(Request $req, Response $res, array $args){
        $token = ValHelper::is_logged($req);
        if (!$token) return self::redirect_notlogged($res);

        $jwt = JWTHelper::decode_jwt($token);
        if (!$jwt) return self::redirect_notlogged($res);

        $data = TasksModel::fetch($jwt["id"]);
        $payload = json_encode($data);
        $res->getBody()->write($payload);

        return $res->withHeader("Content-Type", "application/json");
    }

    public function add_task(Request $req, Response $res, array $args){
        $data = $req->getParsedBody();
        $res->getBody()->write("Not implemented yet");
        return $res;
    }

    public function delete_task(Request $req, Response $res, array $args){
        $res->getBody()->write("Not implemented yet");
        return $res;
    }
}
