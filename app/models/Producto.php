<?php

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
        } finally {
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

            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $resultado;
        }
    }

    public static function CargarCSV($menuCSV)
    {
        try {
            $cantidad = 0;
            $archivo = fopen($menuCSV, 'r');
            $menuNuevo[] = [];
            if ($archivo != null) {
                while (!feof($archivo)) {
                    $menuNuevo[] = fgetcsv($archivo);
                }
                fclose($archivo);
            }
            $resultado = Producto::VaciarMenu();
            if ($resultado["Estado"] == "OK") {
                foreach ($menuNuevo as $item) {
                    if (is_array($item) && count($item)==4) {
                        Producto::CrearProducto($item[2], $item[1], $item[3]);
                        $cantidad++;
                    }
                }
                $resultado = array("Estado" => "OK", "Mensaje" => "Se registro un nuevo menu con $cantidad productos");
            }
            return $resultado;
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            return $resultado;
        }
    }

    public static function VaciarMenu()
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->PrepararConsulta("TRUNCATE TABLE menu;");
            $consulta->execute();
            $resultado = array("Estado" => "OK");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $resultado;
        }
    }

    public static function GuardarCSV($menu)
    {
        try {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha = date('Y-m-d');
            $cantidad = 0;
            header('Content-Type: application/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="backup_menu_' . $fecha . '.csv";');
            $archivo = fopen('php://output', 'w');
            $jsondata = json_decode($menu, true);
            if ($archivo != null) {
                foreach ($jsondata as $item) {
                    fputcsv($archivo, $item);
                    $cantidad++;
                }
                fclose($archivo);
            }
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            return $resultado;
        }
    }

    public static function MasVendido()
    {
        try {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $hoy=date('Y-m-d',strtotime('+1 day'));
            $mesPasado=date('Y-m-d',strtotime('-1 month'));
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT p.id_menu, m.nombre, count(p.id_menu) as cantidad_ventas FROM pedido p 
                                                            INNER JOIN menu m on m.id = p.id_menu WHERE p.fecha BETWEEN :mesPasado AND :hoy 
                                                            GROUP BY(id_menu) HAVING count(p.id_menu) = (SELECT MAX(sel.cantidad_ventas) FROM (SELECT count(p.id_menu) as cantidad_ventas 
                                                            FROM pedido p GROUP BY(id_menu)) sel);");

            $consulta->bindValue(':hoy', $hoy, PDO::PARAM_STR);
            $consulta->bindValue(':mesPasado', $mesPasado, PDO::PARAM_STR);
            $consulta->execute();

            $respuesta = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    
    public static function MenosVendido()
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $hoy=date('Y-m-d',strtotime('+1 day'));
            $mesPasado=date('Y-m-d',strtotime('-1 month'));
            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT p.id_menu, m.nombre, count(p.id_menu) as cantidad_ventas FROM pedido p INNER JOIN menu m
                                                            on m.id = p.id_menu WHERE p.fecha BETWEEN :mesPasado AND :hoy GROUP BY(id_menu) HAVING count(p.id_menu) = 
                                                            (SELECT MIN(sel.cantidad_ventas) FROM 
                                                            (SELECT count(p.id_menu) as cantidad_ventas FROM pedido p GROUP BY(id_menu)) sel);");

            $consulta->bindValue(':hoy', $hoy, PDO::PARAM_STR);
            $consulta->bindValue(':mesPasado', $mesPasado, PDO::PARAM_STR);
            $consulta->execute();

            $respuesta = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }
}
