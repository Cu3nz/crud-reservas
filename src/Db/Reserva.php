<?php 
namespace App\Db;

use PDO;

class Reserva extends Conexion{

private int $id;
private string $nombre;
private string $email;
private int $personas;
private string $comentarios;
private string $imagen;


         public function __construct()
        {
            parent::__construct();
        }




        //?-------------------------- CRUD ----------------------

        public function create(){

            $q = "INSERT INTO reservas (nombre, email , personas , comentarios , imagen) values (:n , :e , :p , :c , :f)";

            $stmt = parent::$conexion -> prepare($q);

            try {
                $stmt -> execute([
                    ':n' => $this -> nombre,
                    ':e' => $this -> email,
                    ':p' => $this -> personas,
                    ':c' => $this -> comentarios,
                    ':f' => $this -> imagen,
                ]);
            } catch (\PDOException $ex) {
                die("Error, en el metodo create " . $ex -> getMessage());
            }

        }


        public static function read(){

            parent::setConexion();
    
            $q = "SELECT * FROM reservas order by id desc";
    
            $stmt = parent::$conexion -> prepare($q);
    
            try {
                $stmt -> execute();
            } catch (\PDOException $ex) {
                die("Error en el metodo read " . $ex -> getMessage());
            }
    
            parent::$conexion = null;
    
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
    
          }

          public static function detalle($idRecogida){

            parent::setConexion();

            $q = "SELECT * FROM reservas where id = :i";

            $stmt = parent::$conexion -> prepare($q);

            try {
                $stmt -> execute([
                    ':i' => $idRecogida
                ]);
            } catch (\PDOException $ex) {
                die("Error en el metodo " . $ex -> getMessage());
            }

            parent::$conexion = null;

            return $stmt -> fetch(PDO::FETCH_OBJ);

          }


          public static function delete ($idPost){
            parent::setConexion();

            $q = "DELETE from reservas where id = :i";

            $stmt = parent::$conexion -> prepare($q);

            try {
                $stmt -> execute([':i' => $idPost]);
            } catch (\PDOException $ex) {
                die("Error en el metodo delete " . $ex -> getMessage());
            }

            parent::$conexion = null;
          }



          public function update($idGet){

            $q = "UPDATE reservas SET  nombre = :n , email = :e , personas = :p , comentarios = :c , imagen = :f  where id = :i";

            $stmt = parent::$conexion -> prepare($q);

            try {
                $stmt -> execute([
                    ':n' => $this -> nombre,
                    ':e' => $this -> email,
                    ':p' => $this -> personas,
                    ':c' => $this -> comentarios,
                    ':f' => $this -> imagen,
                    ':i' => $idGet
                ]);
            } catch (\PDOException $ex) {
                die("Error en el update " . $ex -> getMessage());
            }
            

          }


        //?-------------------------- FAKER  ----------------------


        public static function hayRegistros(){

            parent::setConexion();

            $q = "SELECT * FROM reservas";

            $stmt = parent::$conexion -> prepare($q);

            try {
                $stmt -> execute();
            } catch (\PDOException $ex) {
                die("Erros en el metodo hay registros " . $ex -> getMessage());
            }

            parent::$conexion = null;

            return $stmt -> rowCount(); //* true si hay filas, false si no hay filas

        }




        public static function generarReservas($cantidad){

            if (self::hayRegistros()) return; //* Si hay registros nos vamos de aqui

            //! si no hay registros 

            $faker = \Faker\Factory::create("es_ES");
            $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));

            for ($i=0; $i < $cantidad ; $i++) { 
                
                $nombre = $faker -> firstName();

                $email = $faker -> unique() -> email();

                $personas = random_int(2,10);

                $comentarios = $faker -> text();

                $imagen = "img/" . $faker -> picsum(dir:"./img", width:640, height:480, fullPath:false);


                (new Reserva)
                -> setNombre($nombre)
                -> setEmail($email)
                -> setComentarios($comentarios)
                -> setPersonas($personas)
                -> setImagen($imagen)
                -> create();
            }

        }


        //?--------------------------  OTROS METODOS ----------------------

        public static function EmailRepetido($email , $id = null){
            parent::setConexion();

            $q = ($id == null) ? "SELECT email from reservas where email = :e" : "SELECT email from reservas where email = :e AND id != :i";

            $stmt = parent::$conexion -> prepare($q);

            $options = ($id == null) ? [':e' => $email] : [':e' => $email , ':i' => $id];

            try {
                $stmt -> execute($options);
            } catch (\PDOException $ex) {
                die("Error en el metodo emailRepetido " . $ex -> getMessage());
            }

            parent::$conexion = null;

            return $stmt -> rowCount(); //* Si devuelve una fila es porque ya existe, por lo tanto no podemos insertar el email. si existe una fila devuelve true.

        }


        //?-------------------------- SETTERS ----------------------


/**
 * Set the value of id
 */
public function setId(int $id): self
{
$this->id = $id;

return $this;
}

/**
 * Set the value of nombre
 */
public function setNombre(string $nombre): self
{
$this->nombre = $nombre;

return $this;
}

/**
 * Set the value of personas
 */
public function setPersonas(int $personas): self
{
$this->personas = $personas;

return $this;
}

/**
 * Set the value of comentarios
 */
public function setComentarios(string $comentarios): self
{
$this->comentarios = $comentarios;

return $this;
}

/**
 * Set the value of imagen
 */
public function setImagen(string $imagen): self
{
$this->imagen = $imagen;

return $this;
}

/**
 * Set the value of email
 */
public function setEmail(string $email): self
{
$this->email = $email;

return $this;
}
}

?>