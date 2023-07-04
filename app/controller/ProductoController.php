<?php

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

    public function CargarMenu($request, $response, $args)
    {
        $files = $request->getUploadedFiles();
        $menu = $files["menu"];
        $response->getBody()->write(json_encode(Producto::CargarCSV($menu)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GuardarMenu($request, $response, $args)
    {
        $menu=Producto::Listar();
        $response->getBody()->write(json_encode(Producto::GuardarCSV($menu)));
        return $response;
    }
}