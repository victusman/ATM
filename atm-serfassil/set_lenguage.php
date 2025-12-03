<?php
session_start();

if (isset($_POST['language'])) {
    $selectedLanguage = $_POST['language'];
    // Validar que el idioma sea uno de los permitidos
    $allowedLanguages = ['es', 'en', 'pt'];
    if (in_array($selectedLanguage, $allowedLanguages)) {
        $_SESSION['language'] = $selectedLanguage;
    }
}

// Redirigir de vuelta al login
header('Location: vista/login.php');
exit();
?>