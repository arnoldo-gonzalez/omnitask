<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Interfaces\UserInterface;

class UserController implements UserInterface {
    protected function is_logged() {
    }

    public function index_sign_in(Request $req, Response $res, array $args) {
        $view = Twig::fromRequest($req);
        return $view->render($res, "sign_in.html", [])
    }

    public function index_sign_up(Request $req, Response $res, array $args) {
        $view = Twig::fromRequest($req);
        return $view->render($res, "sign_up.html", [])
    }

    public function sign_in(Request $req, Response $res, array $args) {
        $res->getBody->write("Not implemented yet");
        return $res
    }

    public function sign_up(Request $req, Response $res, array $args) {
        $res->getBody->write("Not implemented yet");
        return $res
    }

    public function delete_user(Request $req, Response $res, array $args) {
        $res->getBody->write("Not implemented yet");
        return $res
    }

    public function change_user_data(Request $req, Response $res, array $args) {
        $res->getBody->write("Not implemented yet");
        return $res
    }
}
