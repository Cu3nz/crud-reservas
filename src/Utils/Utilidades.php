<?php 
namespace App\Utils;

use App\Db\Reserva;

require_once __DIR__."/../../vendor/autoload.php";

const MAY_ON = 1;

const MAY_OFF = 0;

class Utilidades{

    public static function sanearCadenas($valor , $modo = MAY_OFF){

        return ($modo == MAY_ON) ? ucfirst(htmlspecialchars(trim($valor))) : htmlspecialchars(trim($valor));

    }


    public static function errorCampoTexto ($campo  , $valor , $logitud){

        if (strlen($valor) < $logitud){
            $_SESSION[$campo] = "Error el campo $campo tiene que tener $logitud de caracteres";
            return true; //! hay error
        
        }
        return false; //* No hay error
    }

    public static function errorCampoNumerico($campo , $valor , $min ,  $max){

        if ($valor < $min || $valor > $max){
            $_SESSION[$campo] ="Error el numero de personas tiene que estar entre el rango de $min y $max";
            return true; //! error
        }

        return false; //* no error

    }


    public static function mostrarErrores($error_sesion){
        if (isset($_SESSION[$error_sesion])){
            echo "<p class='italic text-red-600 mt-2'>{$_SESSION[$error_sesion]}</p>";
            unset($_SESSION[$error_sesion]);
        }
    }

    public static function validarEmail ($campo , $email){

        if (!filter_var($email , FILTER_VALIDATE_EMAIL)){
            $_SESSION[$campo] = "Error el email no es valido";
            return true; //! hay error
        }
        return false; //* no error
    }


    public static array $tiposMime = [
        'image/gif',
         'image/png',
          'image/jpeg',
           'image/bmp',
            'image/webp'
    ];


    public static function errorTipoFotoYSize($tipo, $size){

        if (!in_array($tipo , self::$tiposMime)){
            $_SESSION['Imagen'] = "Error la imagen que has subido no es una imagen";
            return true;
        }

        if ($size > 200000){
            $_SESSION['Imagen'] = "Error la imagen no puede superar los 2MB";
            return true;
        }
        return false;
    }


    public static function errorEmailRepetido($campo , $email , $id = null){

      if (Reserva::EmailRepetido($email , $id)){ //* Si la funcion EmailRetido devuelve true, es porque ya existe el email en la base de datos, por lo tanto error.
        $_SESSION[$campo] = "Error, el email ya esta usado en la base de datos";
        return true; //! Hay error
      }
      return false; //* No hay error.

    }
    
    
   


}

?>