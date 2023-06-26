<?php
include_once ('./models/Usuario.php');
include_once ('./interfaces/IApiUsable.php');
class UsuarioController extends Usuario implements IApiUsable
{ 
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
}