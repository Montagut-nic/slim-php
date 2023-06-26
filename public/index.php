<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Selective\BasePath\BasePathMiddleware;

require __DIR__ . '../../vendor/autoload.php';
include_once ('../app/db/AccesoDatos.php');
include_once ('../app/interfaces/IApiUsable.php');
include_once ('../app/models/Usuario.php');
include_once ('../app/models/Producto.php');
include_once ('../app/models/Pedido.php');
include_once ('../app/models/Mesa.php');
include_once ('../app/models/JWToken.php');
include_once ('../app/controller/MesaController.php');
include_once ('../app/controller/PedidoController.php');
include_once ('../app/controller/ProductoController.php');
include_once ('../app/controller/UsuarioController.php');
include_once ('../app/middlewares/UsuarioMiddleware.php');

// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/TPComanda/public');

// Add error middleware
$app->addErrorMiddleware(true, true, true);
$app->addRoutingMiddleware();

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
$app->get('/productos/listar[/]', \ProductoController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');

//mesas
$app->post('/mesas/Alta[/]', \MesaController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/mesas/Listar[/]', \MesaController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/mesas/foto[/]', \MesaController::class . ':ActualizarFoto');

//pedidos
$app->post('/pedidos/Alta[/]', \PedidoController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/pedidos/Listar[/]', \PedidoController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');

$app->run();
