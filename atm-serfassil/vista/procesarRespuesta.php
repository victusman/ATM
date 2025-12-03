<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $opcion = $_POST['opcion']; // Obtener la opción seleccionada

    if ($opcion === 'si') {
        header('Location: login.php'); // Redirigir a login.php
        exit;
    } elseif ($opcion === 'no') {
        header('Location: exit.php'); // Redirigir a exit.php
        exit;
    } else {
        // Si la opción no es válida, redirigir a una página de error o al inicio
        header('Location: login.php');
        exit;
    }
}
?>