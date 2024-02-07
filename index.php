<?php
require __DIR__ . "/vendor/autoload.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use App\Controllers\HomeController;
use App\Controllers\UserLogsController;
use App\Controllers\UserActionsController;
use App\Controllers\TasksController;
use App\Middlewares\JsonBodyParser;
use App\Middlewares\TrailingSlash;

// Load de env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$twig = Twig::create("templates", ['cache' => false]);

$app->addRoutingMiddleware();
$app->add(new TrailingSlash());
$app->add(TwigMiddleware::create($app, $twig));
$app->add(new JsonBodyParser);

$app->addErrorMiddleware(true, true, true);

$app->get("/", HomeController::class . ":index" );

$app->get("/user/sign_up", UserLogsController::class . ":index_sign_up" );
$app->post("/user/sign_up", UserLogsController::class . ":sign_up" );
$app->get("/user/sign_in", UserLogsController::class . ":index_sign_in" );
$app->post("/user/sign_in", UserLogsController::class . ":sign_in" );

$app->get("/user/is_loged", UserActionsController::class . ":is_logged" );

$app->get("/user/{id}/tasks", TasksController::class . ":index" );
$app->get("/user/{id}/tasks/get", TasksController::class . ":get_tasks" );
$app->post("/user/{id}/tasks/new", TasksController::class . ":add_task" );

$app->run();
