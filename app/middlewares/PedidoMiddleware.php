<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PedidoMiddleware
{
    public static function ValidarTomarPedido(Request $request, RequestHandler $handler)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'Pendiente') {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido no se encuentra pendiente.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->sector != $payload->tipo) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido pertenece a otro sector.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response = $handler->handle($request);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ValidarPedidoListoParaServir(Request $request, RequestHandler $handler)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'En preparacion') {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido no se encuentra en preparacion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->id_encargado != $payload->id) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Solo el encargado del pedido puede realizar esta accion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response = $handler->handle($request);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ValidarServir(Request $request, RequestHandler $handler)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];
        $pedido = Pedido::ObtenerPorCodigo($codigo);
        $payload = $request->getAttribute("payload")["Payload"];

        if ($pedido == null) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Codigo incorrecto.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->estado != 'Listo para servir') {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Este pedido no se encuentra listo para servir.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($pedido[0]->id_mozo != $payload->id) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("Estado" => "ERROR", "Mensaje" => "Solo el mozo encargado del pedido puede realizar esta accion.")));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response = $handler->handle($request);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
