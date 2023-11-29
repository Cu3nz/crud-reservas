<?php

use App\Db\Reserva;
require_once __DIR__."/../vendor/autoload.php";
session_start();

if (!isset($_POST['id'])){
    header("Location:index.php");
    die();
}


$idPost = $_POST['id'];

/* echo "$idPost";
die(); */

$reserva = Reserva::detalle($idPost);

/* var_dump($reserva);
die(); */

if (basename($reserva -> imagen) != "default.jpeg"){ //* Si el nombr de la imagen que esta guardada en la baase de datos es distinta a la de default la borramos
    unlink("./" . $reserva -> imagen); //? borramos la foto
}

Reserva::delete($idPost);

$_SESSION['mensaje'] = "Reserva eliminada con exito";
header("Location:index.php");


?>