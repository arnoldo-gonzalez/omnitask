<?php
namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface UserActionsInterface {
    /* Recives {id: Int, email: String, password: String} Note: This can be changed */
    public function delete_user(Request $req, Response $res, array $args);

    /* Recives {id: int, email: String, password:String, changes: {Changes to be made} */
    public function change_user_data(Request $req, Response $res, array $args);
}
