<?php
include_once("./db/AccesoDatos.php");
class Producto
{
    public $id;
    public $precio;
    public $nombre;
    public $sector;

    
    public static function CrearProducto($nombre, $precio, $sector)
    {
        $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
        $respuesta = "";
        try {
            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT ID_tipo_empleado FROM tipoempleado WHERE Descripcion = :sector AND Estado = 'A';");

            $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
            $consulta->execute();
            $id_sector = $consulta->fetch();

            if ($id_sector != null) {
                $consulta = $objetoAccesoDato->PrepararConsulta("INSERT INTO menu (nombre, precio, id_sector) 
                                                                VALUES (:nombre, :precio, :id_sector);");

                $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
                $consulta->bindValue(':id_sector', $id_sector[0], PDO::PARAM_INT);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Mensaje" => "Registrado correctamente.");
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Debe ingresar un sector valido");
            }
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function Listar()
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT m.id, m.nombre, m.precio, te.Descripcion as sector FROM menu m INNER JOIN 
                                                        tipoempleado te ON te.ID_tipo_empleado = m.id_sector;");

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Menu");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
}