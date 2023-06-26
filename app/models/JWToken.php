<?php
require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


class JWToken{

    private static $key = "adminadmin";

    private static $token = array(
        "iat" => "",
        "nbf" => "",
        "usuario" => "",
        "tipo" => "",
        "id" => "",
        "nombre" => ""
    );

    public static function CodificarToken($usuario,$tipo,$id,$nombre_empleado){        
        $fecha = new Datetime("now", new DateTimeZone('America/Buenos_Aires'));
        JWToken::$token["iat"] = $fecha->getTimestamp();                
        JWToken::$token["nbf"] = $fecha->getTimestamp();
        JWToken::$token["usuario"] = $usuario; 
        JWToken::$token["tipo"] = $tipo; 
        JWToken::$token["id"] = $id;
        JWToken::$token["nombre"] = $nombre_empleado;
        $jwt = JWT::encode(JWToken::$token, JWToken::$key,"HS256");

        return $jwt;
    }    

    public static function DecodificarToken($token){
        try
        {            
            $payload = JWT::decode($token,new Key(JWToken::$key, 'HS256'));
            $decoded = array("Estado" => "OK", "Mensaje" => "OK", "Payload" => $payload);
        }
        catch(\Firebase\JWT\BeforeValidException $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        catch(\Firebase\JWT\ExpiredException $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "ERROR", "Mensaje" => "$mensaje.");
        }
        catch(Firebase\JWT\SignatureInvalidException $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        catch(Exception $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }        
        return $decoded;
    }
}
?>