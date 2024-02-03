<?php
namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface UserInterface {
    protected function is_logged();

    public function index_sign_in(Request $req, Response $res, array $args);

    public function index_sign_up(Request $req, Response $res, array $args);

    /*Recives {email: String, password: String}*/
    public function sign_in(Request $req, Response $res, array $args);

    /* Recives {name: String, email: String, password: String, premium: Bool, pay_method: String?} */
    public function sign_up(Request $req, Response $res, array $args);

    /* Recives {id: Int, email: String, password: String} Note: This can be changed */
    public function delete_user(Request $req, Response $res, array $args);

    /* Recives {id: int, email: String, password:String, changes: {Changes to be made} */
    public function change_user_data(Request $req, Response $res, array $args);
}
