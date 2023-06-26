<?php

class MesaController extends Mesa implements IApiUsable{  
    
    public function Alta($request, $response, $args){
        $parametros = $request->getParsedBody();
        $codigo = $parametros["codigo"];            

        $payload = Mesa::CrearMesa($codigo);
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request,$response,$args){
        $payload = Mesa::Listar();
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
