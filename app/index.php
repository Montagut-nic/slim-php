<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

//usuarios
$app->post('/empleados/Alta[/]', \UsuarioController::class . ':Alta');
$app->get('/empleados/Listar[/]', \UsuarioController::class . ':ListarTodos');

//productos
$app->post('/productos/Alta[/]', \ProductoController::class . ':Alta');
$app->get('/productos/Listar[/]', \ProductoController::class . ':ListarTodos');

//mesas
$app->post('/mesas/Alta[/]', \MesaController::class . ':Alta');
$app->get('/mesas/Listar[/]', \MesaController::class . ':ListarTodos');

//pedidos

$app->run();
