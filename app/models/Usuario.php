<?php

class Usuario
{
    public $id;
    public $nombre;
    public $tipo;
    public $usuario;
    public $fechaRegistro;
    public $estado;
    public $cantidad_operaciones;

    public static function CrearUsuario($usuario, $clave, $nombre, $tipo)
    {
        $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
        $respuesta = "";
        try {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha = date('Y-m-d H:i:s');

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT ID_tipo_empleado FROM tipoempleado WHERE Descripcion = :tipo AND Estado = 'A';");

            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->execute();
            $id_tipo = $consulta->fetch();

            if ($id_tipo != null) {
                $consulta = $objetoAccesoDato->PrepararConsulta("INSERT INTO empleado (ID_tipo_empleado, nombre_empleado, usuario, 
                clave, fecha_registro, estado) 
                VALUES (:id_tipo, :nombre, :usuario, :clave, :fecha, 'A');");

                $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
                $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
                $consulta->bindValue(':id_tipo', $id_tipo[0], PDO::PARAM_INT);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Mensaje" => "Empleado registrado correctamente.");
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Debe ingresar un tipo de empleado valido");
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

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT empleado.ID_empleado as id, tipoempleado.Descripcion as tipo, empleado.nombre_empleado as nombre, 
                                                        empleado.usuario, empleado.fecha_registro as fechaRegistro, empleado.estado, empleado.cantidad_operaciones
                                                        FROM empleado INNER JOIN tipoempleado on empleado.id_tipo_empleado = tipoempleado.id_tipo_empleado;");

            $consulta->execute();

            $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR EN SQL", "Mensaje" => "$mensaje");
        } finally {
            return $respuesta;
        }
    }

    public static function Login($user, $password)
    {
        $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

        $consulta = $objetoAccesoDato->PrepararConsulta("SELECT te.descripcion as tipo_empleado, em.ID_Empleado, nombre_empleado FROM empleado em
                                                            INNER JOIN tipoempleado te  on em.ID_tipo_empleado = te.ID_tipo_empleado 
                                                            WHERE em.usuario = :user AND em.clave = :password AND em.estado = 'A'");

        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->bindValue(':password', $password, PDO::PARAM_STR);
        $consulta->execute();

        $resultado = $consulta->fetch();
        return $resultado;
    }

    public static function Baja($id_empleado)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE empleado SET estado = 'B' WHERE ID_Empleado = :id");

            $consulta->bindValue(':id', $id_empleado, PDO::PARAM_INT);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Empleado dado de baja.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function Suspender($id_empleado)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE empleado SET estado = 'S' WHERE ID_Empleado = :id");

            $consulta->bindValue(':id', $id_empleado, PDO::PARAM_INT);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Empleado suspendido.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function DarVacaciones($id_empleado)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE empleado SET estado = 'V' WHERE ID_Empleado = :id");

            $consulta->bindValue(':id', $id_empleado, PDO::PARAM_INT);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Empleado de vacaciones.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function SumarOperacion($id_empleado)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE empleado 
                                                            SET cantidad_operaciones = cantidad_operaciones + 1
                                                            WHERE id_empleado = :id_empleado");

            $consulta->bindValue(':id_empleado', $id_empleado, PDO::PARAM_INT);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Operación sumada correctamente.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }
}
