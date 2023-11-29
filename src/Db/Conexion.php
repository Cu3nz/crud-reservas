<?php 
namespace App\Db;

use PDO;

class Conexion{

    protected static $conexion; 

    public function __construct()
    {
        self::setConexion();
    }


    protected static function setConexion(){

        if (self::$conexion != null) return; //* sI HAY CONEXION NOS VAMOS DE AQUI 

        //! SI NO HAY CONEXION LA CREAMOS

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();

        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];

        $dsn = ("mysql:host=$host;dbname=$db;charset=utf8mb4");

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        try {
            self::$conexion = new PDO($dsn , $user , $pass , $options);
        } catch (\PDOException $ex) {
            die("Error en la conexion " . $ex -> getMessage());
        }
    }

}

?>