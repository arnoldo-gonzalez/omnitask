<?php
namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

trait UserActionsTraits {
    private function return_error_json(Response $res, array $errors) {
        $payload = json_encode(["ok" => false, "errors" => $errors]);
        $res->getBody()->write($payload);
        return $res
            ->withHeader('Content-Type', 'application/json');
    }
}
