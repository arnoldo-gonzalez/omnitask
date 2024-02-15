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
use App\Controllers\SubtasksController;
use App\Middlewares\JsonBodyParser;
use App\Middlewares\TrailingSlash;
use App\Middlewares\ErrorHandler;

// Load de env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . "/templates", ['cache' => false]);

$app->addRoutingMiddleware();
$app->add(new TrailingSlash());
$app->add(TwigMiddleware::create($app, $twig));
$app->add(new JsonBodyParser);

$app->get("/", HomeController::class . ":index" );

$app->get("/user/sign_up", UserLogsController::class . ":index_sign_up" );
$app->post("/user/sign_up", UserLogsController::class . ":sign_up" );
$app->get("/user/sign_in", UserLogsController::class . ":index_sign_in" );
$app->post("/user/sign_in", UserLogsController::class . ":sign_in" );

$app->get("/user/actions/is_logged", UserActionsController::class . ":is_logged" );
$app->get("/user/actions/get_data", UserActionsController::class . ":get_data" );
$app->patch("/user/actions/change", UserActionsController::class . ":change_user_data" );
$app->delete("/user/actions/delete", UserActionsController::class . ":delete_user" );

$app->get("/user/tasks", TasksController::class . ":index" );
$app->get("/user/tasks/get", TasksController::class . ":get_tasks" );
$app->post("/user/tasks/new", TasksController::class . ":add_task" );
$app->delete("/user/tasks/delete", TasksController::class . ":delete_task" );

$app->post("/user/tasks/subtasks/new", SubtasksController::class . ":add_subtask" );
$app->delete("/user/tasks/subtasks/delete", SubtasksController::class . ":delete_subtask" );

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(new ErrorHandler($app));
$app->run();
