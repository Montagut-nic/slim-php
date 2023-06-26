<?php
include_once("./models/Pedido.php");
include_once ('./interfaces/IApiUsable.php');
class PedidoController extends Pedido implements IApiUsable{  
    
    public function Alta($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id_mesa = $parametros["id_mesa"];        
        $id_menu  = $parametros["id_menu"];
        $id_mozo = $parametros["id_mozo"];
        $nombre_cliente = $parametros["cliente"];      

        $response->getBody()->write(json_encode(Pedido::CrearPedido($id_mesa,$id_menu,$id_mozo,$nombre_cliente)));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarTodos($request,$response,$args){
        $payload = Pedido::Listar();
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }
}