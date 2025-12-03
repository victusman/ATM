<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Establecer el idioma predeterminado (español)
$defaultLanguage = 'es';

// Obtener el idioma de la sesión (si está definido)
$language = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

// Cargar el archivo de idioma correspondiente
$translations = include __DIR__ . '/../idioma/' . $language . '.php';

$name = $_SESSION['user'];

// Verificar que current_user esté configurado correctamente
if (!isset($_SESSION['current_user']) && isset($_SESSION['numbercard'])) {
    $_SESSION['current_user'] = $_SESSION['numbercard'];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <title>Menú Principal</title> <!-- Este título no cambia con el idioma -->
    <link rel="stylesheet" href="../css/menu.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['welcome_message']; ?>, <?php echo $name; ?></h1>

        <ul>
            <li><a href="retirar.php"><?php echo $translations['withdraw_money']; ?></a></li>
            <li><a href="deposito.php"><?php echo $translations['deposit_money']; ?></a></li>
            <li><a href="consulta.php"><?php echo $translations['check_balance']; ?></a></li>
            <li><a href="historial.php"><?php echo $translations['transaction_history']; ?></a></li>
            <li><a href="logout.php"><?php echo $translations['logout']; ?></a></li>
        </ul>
        <div class="footer">
            <p><?php echo $translations['footer_text']; ?></p>
        </div>
    </div>
</body>

</html>