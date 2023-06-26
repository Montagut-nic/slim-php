<?php
class AccesoDatos
{
    private static $_objetoAccesoDatos;
    private $_objetoPDO;

    private function __construct()
    {
        try {
            $this->_objetoPDO = new PDO('mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DB'] . ';charset=utf8;port=' . $_ENV['MYSQL_PORT'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $this->_objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {

            print "Error<br/>" . $e->getMessage();

            die();
        }
    }

    public function PrepararConsulta($sql)
    {
        return $this->_objetoPDO->prepare($sql);
    }

    public static function ObtenerObjetoAcceso() 
    {
        if (!isset(self::$_objetoAccesoDatos)) {
            self::$_objetoAccesoDatos = new AccesoDatos();
        }

        return self::$_objetoAccesoDatos;
    }

    
    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
