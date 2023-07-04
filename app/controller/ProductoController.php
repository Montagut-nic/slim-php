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
        $menu = $_FILES['menu']['tmp_name'];
        $response->getBody()->write(json_encode(Producto::CargarCSV($menu)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GuardarMenu($request, $response, $args)
    {
        $menu=json_encode(Producto::Listar());
        Producto::GuardarCSV($menu);
        return $response;
    }

    public function LoMenosVendido($request, $response, $args)
    {
        $response->getBody()->write(json_encode(Producto::MenosVendido()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function LoMasVendido($request, $response, $args)
    {
        $response->getBody()->write(json_encode(Producto::MasVendido()));
        return $response->withHeader('Content-Type', 'application/json');
    }
}