<?php

class UsuarioMiddleware
{
    public static function ValidarToken($request, $response, $next)
    {
        $token = $request->getHeader("token");
        $validacionToken = JWToken::DecodificarToken($token[0]);
        if ($validacionToken["Estado"] == "OK") {
            $request = $request->withAttribute("payload", $validacionToken);
            return $next($request, $response);
        } else {
            $response->getBody()->write(json_encode($validacionToken));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ValidarSocio($request, $response, $next)
    {
        $payload = $request->getAttribute("payload")["Payload"];

        if ($payload->tipo == "Socio") {
            return $next($request, $response);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tienes permiso para realizar esta accion (Solo socios)");
            $response->getBody()->write(json_encode($respuesta));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ValidarMozo($request, $response, $next)
    {
        $payload = $request->getAttribute("payload")["Payload"];
        $tipoEmployee = $payload->tipo;
        if ($tipoEmployee == "Mozo" || $tipoEmployee == "Socio") {
            return $next($request, $response);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tienes permiso para realizar esta accion (Solo mozos)");
            $response->getBody()->write(json_encode($respuesta));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
