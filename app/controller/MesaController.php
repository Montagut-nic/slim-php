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
}
