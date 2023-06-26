<?php
include_once("./db/AccesoDatos.php");
class Pedido
{
    public $codigo;
    public $estado;
    public $mesa;
    public $descripcion;
    public $id_menu;
    public $sector;
    public $nombre_cliente;
    public $nombre_mozo;
    public $id_mozo;
    public $id_encargado;
    public $hora_inicial;
    public $hora_entrega_estimada;
    public $hora_entrega_real;
    public $fecha;
    public $importe;

    public static function CrearPedido($id_mesa, $id_menu, $id_mozo, $nombre_cliente)
    {
        $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
        try {
            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT Count(*) FROM menu m, mesa me, empleado em
                                                            INNER JOIN tipoempleado te ON em.ID_tipo_empleado = te.id_tipo_empleado 
                                                            WHERE m.id = :id_menu AND em.ID_empleado = :id_mozo
                                                            AND me.codigo_mesa = :id_mesa AND em.estado = 'A' AND te.Descripcion = 'Mozo';");

            $consulta->bindValue(':id_menu', $id_menu, PDO::PARAM_INT);
            $consulta->bindValue(':id_mozo', $id_mozo, PDO::PARAM_INT);
            $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
            $consulta->execute();
            $validacion = $consulta->fetch();

            if ($validacion[0] > 0) {
                $codigo = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

                date_default_timezone_set("America/Argentina/Buenos_Aires");
                $fecha = date('Y-m-d');
                $hora_inicial = date('H:i');

                $consulta = $objetoAccesoDato->PrepararConsulta("INSERT INTO pedido (codigo, id_estado_pedidos, fecha, hora_inicial, 
                                                                id_mesa, id_menu, id_mozo, nombre_cliente) 
                                                                VALUES (:codigo, 1, :fecha, :hora_inicial, 
                                                                :id_mesa, :id_menu, :id_mozo, :nombre_cliente);");

                $consulta->bindValue(':id_menu', $id_menu, PDO::PARAM_INT);
                $consulta->bindValue(':id_mozo', $id_mozo, PDO::PARAM_INT);
                $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
                $consulta->bindValue(':nombre_cliente', $nombre_cliente, PDO::PARAM_STR);
                $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
                $consulta->bindValue(':hora_inicial', $hora_inicial, PDO::PARAM_STR);
                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Mensaje" => "Pedido registrado correctamente.");
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Alguno de los ID ingresados es inválido.");
            }
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $respuesta;
        }
    }


    public static function Listar()
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT p.codigo, ep.descripcion as estado, p.id_mesa as mesa, 
                                                         me.nombre as descripcion, p.id_menu, te.descripcion as sector, p.nombre_cliente,
                                                         em.nombre_empleado as nombre_mozo, p.id_mozo, p.id_encargado, p.hora_inicial, p.hora_entrega_estimada,
                                                         p.hora_entrega_real, p.fecha, me.precio as importe
                                                         FROM pedido p
                                                         INNER JOIN estado_pedidos ep ON ep.id_estado_pedidos = p.id_estado_pedidos
                                                         INNER JOIN menu me ON me.id = p.id_menu
                                                         INNER JOIN tipoempleado te ON te.id_tipo_empleado = me.id_sector 
                                                         INNER JOIN empleado em ON em.ID_empleado = p.id_mozo");

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $resultado;
        }
    }
}
