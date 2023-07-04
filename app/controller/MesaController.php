<?php

class MesaController extends Mesa implements IApiUsable
{

    public function Alta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];

        $payload = Mesa::CrearMesa($codigo);
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request, $response, $args)
    {
        $payload = Mesa::Listar();
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ActualizarFoto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $files = $request->getUploadedFiles();
        $codigoMesa = $parametros["codigo"];
        $foto = $files["foto"];
        $ext = Foto::ObtenerExtension($foto);
        if ($ext != "ERROR") {
            $rutaFoto = "./Fotos/Mesas/" . $codigoMesa . "." . $ext;
            Foto::GuardarFoto($foto, $rutaFoto);
            $response->getBody()->write(json_encode(Mesa::AgregarFoto($rutaFoto, $codigoMesa)));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Ocurrio un error.");
            $response->getBody()->write(json_encode($respuesta));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CambiarEstado($request, $response, $args)
    {
        $codigo = $args["codigo"];
        $estado = $args["estado"];
        switch ($estado) {
            case "esperando":
            case "comiendo":
            case "pagando":
                $respuesta = UsuarioMiddleware::ValidarMozo($request, $response, Mesa::CambiarEstadoPedido($codigo, $estado));
                $response->getBody()->write(json_encode($respuesta));
                return $response->withHeader('Content-Type', 'application/json');
                break;
            case "cerrada":
                $respuesta = UsuarioMiddleware::ValidarSocio($request, $response, Mesa::CambiarEstadoPedido($codigo, $estado));
                $response->getBody()->write(json_encode($respuesta));
                return $response->withHeader('Content-Type', 'application/json');
                break;
            default:
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Ocurrio un error. Ingrese un estado: esperando, comiendo, pagando, cerrada.");
                $response->getBody()->write(json_encode($respuesta));
                return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CobrarMesa($request, $response, $args)
    {
        $codigo = $args["codigo"];
        $response->getBody()->write(json_encode(Mesa::Cobrar($codigo)));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
