<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Interfaces\SubtasksInterface;

class SubtasksController implements SubtasksInterface {
    public function add_subtask(Request $req, Response $res, array $args){
        $res->getBody->write("Not implemented yet");
        return $res
    }

    public function delete_subtask(Request $req, Response $res, array $args){
        $res->getBody->write("Not implemented yet");
        return $res
    }
}
