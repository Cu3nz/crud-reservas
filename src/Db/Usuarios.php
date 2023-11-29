<?php

namespace App\Db;

use PDO;
use PDOException;

class Usuarios extends Conexion
{

    private string $id;
    private string $usuario;
    private string $password;
    private string $perfil;
    public function __construct()
    {
        parent::__construct();
    }



    //? -------------------------------- crud ------------------


    public function create (){

        $q = "INSERT INTO usuarios (usuario , password , perfil ) values (:u , :p , :pf)";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute([
                ':u' => $this -> usuario,
                ':p' => $this -> password,
                ':pf' => $this -> perfil,
            ]);
        } catch (\PDOException $ex) {
            die("Error en el metodo create " . $ex -> getMessage());
        }

    }





    public static function login($usuario , $password){ //* Se le pasa el usuario

        parent::setConexion();

        $q = "SELECT usuario , password , perfil from usuarios where usuario = :u"; //* Tenemos que traer los atributos seleccionados para ese usuario.

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute([':u' => $usuario]);
        } catch (\PDOException $ex) {
            die("Error en el metodo login " . $ex -> getMessage());
        }

        parent::$conexion = null;

        $datos = $stmt -> fetch(PDO::FETCH_OBJ);

        //* No existe nada , por lo tanto no existe ningun usuario
         
        if (!$datos) return false;

        //todo si estamos aqui el usuario existe ahora verificamos la contraseña

        if (!password_verify($password , $datos -> password)){ //* Verificamos si la contrasñea introduca por el input es la misma contraseña que esta haseada en la base de datos.
            return false;
        }

        return $datos;

    }


    //? --------------------------------  faker ------------------


    public static function hayRegistros()
    {
        parent::setConexion();

        $q = "select * from usuarios ";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo hayregistros en usuarios " . $ex->getMessage());
        }
        parent::$conexion = null;

        return $stmt->rowCount(); //* devuevve true si hay registros false si no hay registros
    }


    public static function  generarUsuarios($cantidad)
    {

        if (self::hayRegistros()) return; //* si hay registros nos vamos de aqui 

        $faker = \Faker\Factory::create("es_ES");

        for ($i = 0; $i < $cantidad; $i++) {


            $usuario = strtolower($faker->firstName);

            $password = "secret0";

            $perfil = random_int(0, 1);

            (new Usuarios)
            -> setUsuario($usuario)
            -> setPassword($password)
            -> setPerfil($perfil)
            -> create();
        }
    }


    //? --------------------------------  otros metodos ------------------
    //? -------------------------------- setters ------------------


    /**
     * Set the value of id
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of usuario
     */
    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * Set the value of perfil
     */
    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }
}
