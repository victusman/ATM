<?php
session_start(); // Iniciar sesión si es necesario

// Establecer el idioma predeterminado (español)
$defaultLanguage = 'es';

// Obtener el idioma de la sesión (si está definido)
$language = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

// Cargar el archivo de idioma correspondiente
$translations = include __DIR__ . '/../idioma/' . $language . '.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <title><?php echo $translations['thank_you']; ?></title>
    <link rel="stylesheet" href="../css/exit.css"> <!-- Vincula el archivo CSS -->
    <script>
        // Redirigir a login.php después de 6 segundos
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 6000); // 6000 milisegundos = 6 segundos
    </script>
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['thank_you']; ?></h1>
        <p><?php echo $translations['come_back_soon']; ?></p>
        <p class="contador"><?php echo $translations['redirect_message']; ?></p>
    </div>
</body>

</html>