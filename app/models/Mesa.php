<?php

use FPDF\Fpdf;

class Mesa
{
    public $codigo;
    public $estado;
    public $foto;

    public static function CrearMesa($codigo)
    {
        $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
        $respuesta = "";
        try {
            $consulta = $objetoAccesoDato->PrepararConsulta("INSERT INTO mesa (codigo_mesa, estado) 
                                                            VALUES (:codigo, 'Cerrada');");

            $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Mesa registrada correctamente.");
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

            $consulta = $objetoAccesoDato->PrepararConsulta("SELECT codigo_mesa as codigo, estado, foto FROM mesa");

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }

    public static function AgregarFoto($rutaFoto, $codigoMesa)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();

            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE mesa SET foto = :rutaFoto WHERE codigo_mesa = :codigo");

            $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':rutaFoto', $rutaFoto, PDO::PARAM_STR);

            $consulta->execute();

            $resultado = array("Estado" => "OK", "Mensaje" => "Foto actualizada correctamente.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }


    public static function CambiarEstadoPedido($codigoMesa,$estado)
    {
        try {
            $objetoAccesoDato = AccesoDatos::ObtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->PrepararConsulta("UPDATE mesa SET estado = :estado WHERE codigo_mesa = :codigo");
            
            switch ($estado){
                case "esperando":
                    $estadoMesa='Con cliente esperando pedido';
                    break;
                case "comiendo":
                    $estadoMesa='Con clientes comiendo';
                    break;
                case "pagando":
                    $estadoMesa='Con clientes pagando';
                    break;
                case "cerrada":
                    $estadoMesa='Cerrada';
                    break;
            }

            $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estadoMesa, PDO::PARAM_STR);
            $consulta->execute();

            $resultado = array("Estado" => "OK", "Mensaje" => "Cambio de estado exitoso.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    public static function Cobrar($codigoMesa)
    {
        try {
            $pedidos = Pedido::ListarPorMesa($codigoMesa);
            $resultado = Mesa::GenerarFactura($codigoMesa,$pedidos);
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }

    public static function GenerarFactura($codigoMesa, $pedidos)
    {
        try {
            $importeFinal = 0;
            $nombreCliente = trim($pedidos[0]->nombre_cliente);

            $pdf = new FPDF("P", "mm", "A4");
            $pdf->AddPage();
            $pdf->SetFont("Arial", "B", 12);
            //ancho,largo,contenido,borde(T/F),salto de linea(T/F),alineacion(C=center/R=right)
            $pdf->Cell(50, 10, 'Pedido', 1, 0, "C");
            $pdf->Cell(50, 10, 'Precio', 1, 0, "C");
            $pdf->Cell(50, 10, 'Importe', 1, 1, "R");

            foreach ($pedidos as $pedido) {
                if ($pedido->estado == "Entregado") {
                    $importeFinal += $pedido->importe;
                    $pdf->Cell(50, 10, $pedido->descripcion, 1, 0, "C");
                    $pdf->Cell(50, 10, "$ " . $pedido->importe, 1, 1, "C");
                }
            }
            $pdf->Cell(50, 10, '', 1, 0, "C");
            $pdf->Cell(50, 10, '', 1, 0, "C");
            $pdf->Cell(50, 10, "$ " . $importeFinal, 1, 0, "R");

            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha = date('Y-m-d');

            $pdf->Output("D", "factura_" . $codigoMesa . "_" . $fecha . "_" . $nombreCliente . ".pdf", true);

            $resultado = Pedido::Finalizar($codigoMesa);;

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $resultado;
        }
    }
}