<?php

class PedidoController extends Pedido implements IApiUsable
{

    public function Alta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_mesa = $parametros["id_mesa"];
        $id_menu  = $parametros["id_menu"];
        $id_mozo = $parametros["id_mozo"];
        $nombre_cliente = $parametros["cliente"];

        $response->getBody()->write(json_encode(Pedido::CrearPedido($id_mesa, $id_menu, $id_mozo, $nombre_cliente)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request, $response, $args)
    {
        $payload = Pedido::ListarPedidosTodos();
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarPendientes($request, $response, $args)
    {
        $payload = $request->getAttribute("payload")["Payload"];
        $id_empleado = $payload->id;
        $sector = $payload->tipo;
        $response->getBody()->write(json_encode(Pedido::ListarPedidosPendientes($sector, $id_empleado)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TomarPedidoPendiente($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $minutosEstimados = $parametros["minutosEstimados"];
        $payload = $request->getAttribute("payload")["Payload"];
        $id_encargado = $payload->id;
        $response->getBody()->write(json_encode(Pedido::TomarPedido($codigo, $id_encargado, $minutosEstimados)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ServirPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $response->getBody()->write(json_encode(Pedido::Servir($codigo)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TiempoRestantePedido($request, $response, $args)
    {
        $codigo = $args["codigo"];
        $response->getBody()->write(json_encode(Pedido::TiempoRestante($codigo)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function PedidoListoParaServir($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $response->getBody()->write(json_encode(Pedido::ListoParaServir($codigo)));
        return $response->withHeader('Content-Type', 'application/json');
    }

}
