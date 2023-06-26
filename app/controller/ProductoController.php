<?php
include_once("./models/Producto.php");
include_once ('./interfaces/IApiUsable.php');
class ProductoController extends Producto implements IApiUsable
{
    public function Alta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros["nombre"];
        $precio = $parametros["precio"];
        $sector = $parametros["sector"];

        $payload = Producto::CrearProducto($nombre, $precio, $sector);
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request, $response, $args)
    {
        $payload = json_encode(Producto::Listar());
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}