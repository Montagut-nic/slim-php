<?php
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
$app->post('/empleados/login[/]', \UsuarioController::class . ':LogOperacion');  
$app->post('/empleados/alta[/]', \UsuarioController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); ;
$app->get('/empleados/listar[/]', \UsuarioController::class . ':ListarTodos');

//productos
$app->post('/productos/alta[/]', \ProductoController::class . ':Alta');
$app->get('/productos/listar[/]', \ProductoController::class . ':ListarTodos');

//mesas
$app->post('/mesas/Alta[/]', \MesaController::class . ':Alta');
$app->get('/mesas/Listar[/]', \MesaController::class . ':ListarTodos');

//pedidos
$app->post('/pedidos/Alta[/]', \PedidoController::class . ':Alta');
$app->get('/pedidos/Listar[/]', \PedidoController::class . ':ListarTodos');

$app->run();
