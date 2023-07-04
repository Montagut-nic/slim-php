<?php
class PedidoMiddleware
{
    public static function ValidarTomarPedido($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];  
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'Pendiente') {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido no se encuentra pendiente.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->sector != $payload->tipo) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido pertenece a otro sector.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $next($request, $response);
        }
    }

    public static function ValidarPedidoListoParaServir($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];  
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'En Preparacion') {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido no se encuentra en preparacion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->id_encargado != $payload->id) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Solo el encargado del pedido puede realizar esta accion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $next($request, $response);
        }
    }

    public static function ValidarServir($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];  
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'Listo para Servir') {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" =>"Este pedido no se encuentra listo para servir.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->id_mozo != $payload->id) {
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" =>"Solo el mozo encargado del pedido puede realizar esta accion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $next($request, $response);
        }
    }
}