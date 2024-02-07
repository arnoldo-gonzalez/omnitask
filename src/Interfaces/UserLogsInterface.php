<?php
namespace App\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface UserLogsInterface {
    /*Recives {email: String, password: String}*/
    public function sign_in(Request $req, Response $res, array $args);

    /* Recives {name: String, email: String, password: String, premium: Bool, pay_method: String?} */
    public function sign_up(Request $req, Response $res, array $args);
}
