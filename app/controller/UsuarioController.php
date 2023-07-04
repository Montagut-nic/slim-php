<?php

class UsuarioController extends Usuario implements IApiUsable
{ 

    public function LogOperacion($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $retorno = Usuario::Login($usuario, $clave);

        if ($retorno["tipo_empleado"] != "") {
            $token = JWToken::CodificarToken($usuario, $retorno["tipo_empleado"], $retorno["ID_Empleado"], $retorno["nombre_empleado"]);
            $respuesta = array("Estado" => "OK", "Mensaje" => "Logueado exitosamente.", "Token" => $token, "Nombre_Empleado" => $retorno["nombre_empleado"]);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Usuario o clave invalidos.");
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response->withHeader('Content-Type', 'application/json');
    }   

    public function Alta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $nombre = $parametros["nombre"];
        $tipo = $parametros["tipo"];

        $payload = json_encode(Usuario::CrearUsuario($usuario, $clave, $nombre, $tipo));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request, $response, $args)
    {
        $payload = json_encode(Usuario::Listar());
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BajaEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $response->getBody()->write(json_encode(Usuario::Baja($id)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function SuspenderEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $response->getBody()->write(json_encode(Usuario::Suspender($id)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VacacionesEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $response->getBody()->write(json_encode(Usuario::DarVacaciones($id)));
        return $response->withHeader('Content-Type', 'application/json');
    }
}