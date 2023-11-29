<?php

use App\Db\Usuarios;
use App\Utils\Utilidades;

require_once __DIR__."/../../vendor/autoload.php";
session_start();
Usuarios::generarUsuarios(5);


    if (isset($_POST['btn'])){

        $usuario = Utilidades::sanearCadenas(($_POST['usuario']));

        $password = Utilidades::sanearCadenas($_POST['password']);

        //todo validaciones

        $errores = false;
        
        if (Utilidades::errorCampoTexto('usuario' , $usuario , 3)){
            $errores = false;
        }

        if (Utilidades::errorCampoTexto('password' , $password , 5)){
            $errores = true;
        }


        if ($errores){
            header("Location:{$_SERVER['PHP_SELF']}");
            die();
        }

     /*    echo $usuario; 
        echo $password;
        die(); */

        
        $datos = Usuarios::login($usuario , $password);
        
        /* var_dump($datos);
        die(); */


        if (!$datos){ //* Si no exite datos , osea esta mal el email , la password o ambas
            $_SESSION['errorSesion'] = "Error, no se ha podido acceder a la cuenta, revisa e intentalo de nuevo";
            header("Location:{$_SERVER['PHP_SELF']}");
            die();
        } else { //* Si estamos aqui es porque hemos realizado login 

            //todo guardamos  en las variables de sesion el usuario y el perfil.

            $_SESSION['Usuario'] = $usuario; //? Guardamos en una sesion el usuario que se ha hecho login.

            $_SESSION['perfil'] = ($datos -> perfil == 0) ? "USUARIO" : "VIP"; //* Si vale 0 es usuario normal y si es 1 es VIP.

            //todo y nos vamos a la pagina index.php donde esta la tabla vamos 
            header("Location:./../index.php");
        }

    } else {

        ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <title>Inicia sesión</title>
</head>

<body>
    <section class="bg-gray-500 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Inicia sesión en tu cuenta
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div>
                            <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="sergio? estoy ya amargado">
                            <?php 
                            Utilidades::mostrarErrores('usuario');
                            ?>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                            <input type="password" value="secret0" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php 
                            Utilidades::mostrarErrores("password");
                            ?>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Recuérdame</label>
                                </div>
                            </div>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">¿Has olvidado tu contraseña?</a>
                        </div>
                        <button type="submit" name="btn" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Iniciar sesión</button>
                        <?php 
                            Utilidades::mostrarErrores('errorSesion');
                        ?>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            ¿Aún no tienes una cuenta? <a href="register.php" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Registrate</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>

<?php 
    }
?>