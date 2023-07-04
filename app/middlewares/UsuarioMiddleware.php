<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class UsuarioMiddleware
{
    public static function ValidarToken(Request $request, RequestHandler $handler)
    {
        $header = $request->getHeader("Authorization");
        if (!empty($header)) {
            $token = explode(' ', $header[0]);
            $validacionToken = JWToken::DecodificarToken($token[1]);
        }
        if ($validacionToken["Estado"] == "OK") {
            $request = $request->withAttribute("payload", $validacionToken);
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode($validacionToken));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ValidarSocio(Request $request, RequestHandler $handler)
    {
        $payload = $request->getAttribute("payload")["Payload"];

        if ($payload->tipo == "Socio") {
            $response = $handler->handle($request);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tienes permiso para realizar esta accion (Solo socios)");
            $response = new Response();
            $response->getBody()->write(json_encode($respuesta));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ValidarMozo(Request $request, RequestHandler $handler)
    {
        $payload = $request->getAttribute("payload")["Payload"];
        $tipoEmployee = $payload->tipo;
        if ($tipoEmployee == "Mozo" || $tipoEmployee == "Socio") {
            $response = $handler->handle($request);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tienes permiso para realizar esta accion (Solo mozos)");
            $response = new Response();
            $response->getBody()->write(json_encode($respuesta));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function SumarOperacion(Request $request, RequestHandler $handler)
    {
        $payload = $request->getAttribute("payload")["Payload"];
        Usuario::SumarOperacion($payload->id);
        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
