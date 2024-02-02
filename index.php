<?php
require __DIR__ . "/vendor/autoload.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use App\Controllers\ContactController;
use App\Controllers\BlogController;

$app = AppFactory::create();
$twig = Twig::create("templates", ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get("/", "App\Controllers\HomeController:index" );

$app->run();
