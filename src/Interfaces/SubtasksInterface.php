<?php
namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface SubtasksInterface {
    public function add_subtask(Request $req, Response $res, array $args);

    public function delete_subtask(Request $req, Response $res, array $args);
}
