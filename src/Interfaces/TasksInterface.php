<?php
namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface TasksInterface {
    public function index(Request $req, Response $res, array $args);

    public function get_tasks(Request $req, Response $res, array $args);

    public function add_task(Request $req, Response $res, array $args);

    public function delete_task(Request $req, Response $res, array $args);
}

