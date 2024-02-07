<?php
namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

trait UserLogsTraits {
    public function index_sign_in(Request $req, Response $res, array $args) {
        $view = Twig::fromRequest($req);
        return $view->render($res, "sign_in.html", []);
    }

    public function index_sign_up(Request $req, Response $res, array $args) {
        $view = Twig::fromRequest($req);
        return $view->render($res, "sign_up.html", []);
    }
}
