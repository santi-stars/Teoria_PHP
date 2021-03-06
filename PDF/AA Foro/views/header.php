<?php
require_once '..\controllers\session_controller.php';
// inicializamos el session_controler

$session = new SessionController();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($session->get('user') != false) {
        $session->delete();
        header("location: ..\index.php");
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="..\png\icono_blascobikes.ico">
    <link rel="stylesheet" href="../css/style.css">
    <title>Foro Blasco Bikes</title>
</head>
<body>
<img id="fondo-header" src="..\PNG\header_foro_blasco_bikes.png">
<!--<h1>Foro Blasco Bikes</h1>-->
<div id="wrapper">
    <div id="menu">
        <a class="item" href="..\index.php">Inicio</a>
        <!-- se obtiene del enlace el valor de sessionExists para mostrar un contenido u otro -->
        <?php $sessionExists = $_GET['sessionExists']; ?>

        <!-- se mostrará un mensaje de bienvenida si la sesión está iniciada -->
        <div id="userbar">
            <?php if ($sessionExists === "true") : ?>
                <a class="item" href=''><?php echo "Bienvenido " ?><strong
                            class="user-name"> <?php echo $session->get('user'); ?></strong></a> -
                <a class="item" href='home.php?sessionExists=false'>Cerrar sesión</a>
            <?php endif; ?>
            <?php if ($sessionExists === "false") : ?>
                <?php $session->delete(); ?>
                <a class="item" href='login.php'>Iniciar sesión</a> -
                <a class="item" href='register.php'>Regístrate</a>
            <?php endif; ?>
        </div>
    </div>