<?php



use App\Db\Reserva;
session_start();
//todo comprobacion, si no existe una sesion de email, te vas para fuera , te vas para login

if (!isset($_SESSION['Usuario'])){
    header("Location:./auth/login.php");
    die();
}
require_once __DIR__."/../vendor/autoload.php";

Reserva::generarReservas(5);

$reserva = Reserva::read();


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

    <?php 

        echo "Bienvenido {$_SESSION['Usuario']}";

    ?>

    <div class="container p-12 mx-auto">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-row-reverse mb-2 ">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="create.php">Crear una nueva reserva</a>
            </div>
            <div class="flex flex-row-reverse mb-2 ">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="auth/cerrar_sesion.php">Cerrar Sesion </a>
            </div>
            <?php 
            if (!$reserva){
                echo <<<TXT
                <div>
                    <p>No hay registros en la tabla</p>
                </div>
                TXT;
            } else {
            ?>
            
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            NOMBRE
                        </th>
                        <th scope="col" class="px-6 py-3">
                            PERSONAS
                        </th>
                        <th scope="col" class="px-6 py-3">
                            COMENTARIOS
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ACCIONES
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    foreach ($reserva as $item) {
                        echo <<<TXT
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="./{$item -> imagen}" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{$item -> nombre}</div>
                                <div class="font-normal text-gray-500">{$item -> email}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            {$item -> personas}
                        </td>
                        <td class="px-6 py-4">
                            {$item -> comentarios}
                        </td>
                        <td class="px-6 py-4">
                        <form action="delete.php" method="post">
                            <input type="hidden" name="id" value="{$item -> id}">
                            <a href="detalle.php?id={$item -> id}"><i class="fas fa-info text-blue-600 mr-2"></i></a>
                            <a href="update.php?id={$item -> id}"><i class="fas fa-edit text-yellow-600 mr-2"></i></a>
                            <button type="submit"><i class="fas fa-trash text-red-600"></i></button>
                        </form>
                        </td>
                        
                    </tr>
                    TXT;
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }
            ?>
        </div>
    </div>
</body>

<?php 
if (isset($_SESSION['mensaje'])){
    echo <<<TXT
    <script>
    Swal.fire({
        icon: "success",
        title: "{$_SESSION['mensaje']}",
        showConfirmButton: false,
        timer: 1500
      });
    </script>
    TXT;
    unset($_SESSION['mensaje']);
}

?>
</html>
