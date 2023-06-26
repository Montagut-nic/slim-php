<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
include_once ('./controller/MesaController.php');
include_once ('./controller/PedidoController.php');
include_once ('./controller/ProductoController.php');
include_once ('./controller/UsuarioController.php');
include_once ('./middlewares/UsuarioMiddleware.php');

// Instantiate App
$app = AppFactory::create();
$app->setBasePath("/TPComanda/PHP/app");

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

//usuarios
$app->post('/empleados/login[/]', \UsuarioController::class . ':LogOperacion');  
$app->post('/empleados/alta[/]', \UsuarioController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/empleados/listar[/]', \UsuarioController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');  

//productos
$app->post('/productos/alta[/]', \ProductoController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('[/]', \ProductoController::class . ':ListarTodos');
//->add(\UsuarioMiddleware::class . ':ValidarSocio')
//->add(\UsuarioMiddleware::class . ':ValidarToken');

//mesas
$app->post('/mesas/Alta[/]', \MesaController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/mesas/Listar[/]', \MesaController::class . ':ListarTodos');

//pedidos
$app->post('/pedidos/Alta[/]', \PedidoController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/pedidos/Listar[/]', \PedidoController::class . ':ListarTodos');

$app->run();
