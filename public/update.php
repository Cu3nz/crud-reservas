<?php

use App\Db\Reserva;
use App\Utils\Utilidades;

use const App\Utils\MAY_ON;

session_start();

require_once __DIR__ . "/../vendor/autoload.php";

if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}

$idGet = $_GET['id'];

$reserva = Reserva::detalle($idGet);

/* echo "$idGet";
die(); */

if (isset($_POST['btn'])) {
    $nombre = Utilidades::sanearCadenas($_POST['nombre'], MAY_ON);
    $email = Utilidades::sanearCadenas($_POST['email']);
    $personas = (int) trim($_POST['personas']);
    $comentarios = Utilidades::sanearCadenas($_POST['comentarios'], MAY_ON);


    //todo validaciones de campos 

    $errores = false;

    if (Utilidades::errorCampoTexto('nombre', $nombre, 5)) {
        $errores = true;
    }

    if (Utilidades::errorCampoTexto('comentarios', $comentarios, 5)) {
        $errores = true;
    }

    if (Utilidades::errorCampoNumerico('personas', $personas, 2, 10)) {
        $errores = true;
    }


    if (Utilidades::validarEmail('email', $email)) {
        $errores = true;
    }

    if (Utilidades::errorEmailRepetido('email', $email , $idGet)) {
        $errores = true;
    }


    //todo validacion de la imagen 

    if (!$errores) { //* Si no hay errores me lo compruebas
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { //* si se ha subido una imagen a la carpeta temporal

            //todo comprobamos si es una imagen y si no supera los 2MB.

            if (Utilidades::errorTipoFotoYSize($_FILES['imagen']['type'], $_FILES['imagen']['size'])) { //? Si la extension no esta definida en el array, la funcion devuelve true, por lo tanto ERROR 
                $errores = true;
            } else { //* Si pasamos las validaciones.....

                $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name']; //? Esto devuelve img/98685968_perfil.jpg;

                //todo Validamos si se ha podido mover la imagen que esta en la carpeta temporal a la de img

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], "./" . $imagen)) { //? Si no se ha podido mover la imagen subida de la carpeta temporal a la de img, adivina..... ERROR. 
                    $errores = true;
                } else {


                    //* Si llega aqui es porque se ha subido, por lo tanto tengo que eliminar la anterior y subir la nueva

                    if (basename($reserva->imagen) != "default.jpeg") { //* Si el nombr de la imagen que esta guardada en la baase de datos es distinta a la de default la borramos
                        unlink("./" . $reserva->imagen); //? borramos la foto
                    }
                }
            }
        } else {

            $imagen = "./img/default.jpeg"; //? Si no sube una imagen le ponemos esta por defecto

        }
    }



    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$idGet");
        die();
    }


    //* Si llegamos aqui es porque hemos pasado las validaciones por lo tanto creamos el objeto


    (new Reserva)
        ->setNombre($nombre)
        ->setEmail($email)
        ->setComentarios($comentarios)
        ->setPersonas($personas)
        ->setImagen($imagen)
        ->update($idGet);
    $_SESSION['mensaje'] = "Reservada Actualizada con exito";
    header("Location:index.php");
} else {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Fontawesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>

    <body style="background-color: burlywood;">
        <div class="container p-12 mx-auto">
            <h1 class="flex justify-center text-xl font-bold text-white m-3">Actualizar Reserva</h1>
            <div class="w-3/4 mx-auto p-6 rounded-xl bg-gray-400">
                <!-- Si vamos a subir archivos hay que poner el enctype="multipart/form-data" en el formulario. -->
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?id=$idGet" ?>" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nombre</label>
                        <input type="text" name="nombre" value="<?php echo $reserva -> nombre ?>" id="nombre" placeholder="Nombre..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <?php
                        Utilidades::mostrarErrores('nombre')
                        ?>
                    </div>
                    <div class="mb-6">
                        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Email</label>
                        <input type="text" name="email" value=" <?php echo $reserva -> email ?>" id="nombre" placeholder="Email..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <?php
                        Utilidades::mostrarErrores('email')
                        ?>
                    </div>
                    <div class="mb-6">
                        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Personas</label>
                        <input type="number" name="personas"  value="<?php echo $reserva -> personas ?>" id="descripcion" placeholder="Numero de personas..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <?php
                        Utilidades::mostrarErrores('personas')
                        ?>
                    </div>
                    <div class="mb-6">
                        <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Comentarios</label>
                        <textarea name="comentarios" id="" cols="100" rows="10"> <?php echo $reserva -> comentarios  ?></textarea>
                        <?php
                        Utilidades::mostrarErrores('comentarios')
                        ?>
                    </div>
                    <div class="mb-6">
                        <div class="flex w-full">
                            <div class="w-1/2 mr-2">
                                <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    IMAGEN</label>
                                <input type="file" id="imagen" oninput="img.src=window.URL.createObjectURL(this.files[0])" name="imagen" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                <?php
                                Utilidades::mostrarErrores('Imagen');
                                ?>
                            </div>
                            <div class="w-1/2">
                                <img src="<?php echo "./" . $reserva->imagen ?>" class="h-72 rounded w-full object-cover border-4 border-black" id="img">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row-reverse">
                        <button type="submit" name="btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-save mr-2"></i>Actualizar Reserva
                        </button>
                        <button type="reset" class="mr-2 text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-blue-800">
                            <i class="fas fa-paintbrush mr-2"></i>Limpiar campos
                        </button>
                        <a href="index.php" class="mr-2 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <i class="fas fa-xmark mr-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>
<?php
}
?>