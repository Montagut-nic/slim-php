<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
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
include_once ('../app/models/Foto.php');
include_once ('../app/controller/MesaController.php');
include_once ('../app/controller/PedidoController.php');
include_once ('../app/controller/ProductoController.php');
include_once ('../app/controller/UsuarioController.php');
include_once ('../app/middlewares/UsuarioMiddleware.php');
include_once ('../app/middlewares/PedidoMiddleware.php');

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
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/empleados/listar[/]', \UsuarioController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->delete('/empleados/{id}[/]', \UsuarioController::class . ':BajaEmpleado')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');  
$app->delete('/empleados/suspender/{id}[/]', \UsuarioController::class . ':SuspenderEmpleado')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->delete('/empleados/vacaciones/{id}[/]', \UsuarioController::class . ':VacacionesEmpleado')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 

//productos
$app->post('/productos/alta[/]', \ProductoController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/productos/listar[/]', \ProductoController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/productos/cargar[/]', \ProductoController::class . ':CargarMenu')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/productos/guardar[/]', \ProductoController::class . ':GuardarMenu');
//->add(\UsuarioMiddleware::class . ':SumarOperacion')
//->add(\UsuarioMiddleware::class . ':ValidarSocio')
//->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/productos/MasVendido[/]', \ProductoController::class . ':LoMasVendido')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/productos/MenosVendido[/]', \ProductoController::class . ':LoMenosVendido')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');

//mesas
$app->post('/mesas/alta[/]', \MesaController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/mesas/listar[/]', \MesaController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/mesas/foto[/]', \MesaController::class . ':ActualizarFoto')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/mesas/{estado}/{codigo}[/]', \MesaController::class . ':CambiarEstado')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/cerrar/{codigo}[/]', \MesaController::class . ':CerrarMesa')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/cobrar/{codigo}[/]', \MesaController::class . ':CobrarMesa');
//->add(\UsuarioMiddleware::class . ':SumarOperacion')
//->add(\UsuarioMiddleware::class . ':ValidarSocio')
//->add(\UsuarioMiddleware::class . ':ValidarToken'); 

//pedidos
$app->post('/pedidos/alta[/]', \PedidoController::class . ':Alta')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/pedidos/listar[/]', \PedidoController::class . ':ListarTodos')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarSocio')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->get('/pedidos/listar/pendientes[/]', \PedidoController::class . ':ListarPendientes')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/pedidos/tomar[/]', \PedidoController::class . ':TomarPedidoPendiente')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\PedidoMiddleware::class . ':ValidarTomarPedido')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/pedidos/listo[/]', \PedidoController::class . ':PedidoListoParaServir')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\PedidoMiddleware::class . ':ValidarPedidoListoParaServir')
->add(\UsuarioMiddleware::class . ':ValidarToken');
$app->post('/pedidos/servir[/]', \PedidoController::class . ':ServirPedido')
->add(\UsuarioMiddleware::class . ':SumarOperacion')
->add(\PedidoMiddleware::class . ':ValidarServir')
->add(\UsuarioMiddleware::class . ':ValidarMozo')
->add(\UsuarioMiddleware::class . ':ValidarToken'); 
$app->get('/pedidos/tiempo/{codigo}[/]', \PedidoController::class . ':TiempoRestantePedido');

$app->run();
