<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['current_user'])) {
    header('Location: login.php');
    exit;
}

// Establecer el idioma predeterminado (español)
$defaultLanguage = 'es';

// Obtener el idioma de la sesión (si está definido)
$language = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

// Cargar el archivo de idioma correspondiente
$translations = include __DIR__ . '/../idioma/' . $language . '.php';

// Verificar si hay detalles del depósito en la sesión
if (!isset($_SESSION['detalle_deposito'])) {
    echo $translations['deposit_details_not_found'];
    exit;
}

// Obtener los detalles del depósito
$detalleDeposito = $_SESSION['detalle_deposito'];
$fecha = $detalleDeposito['fecha'];
$monto = $detalleDeposito['monto'];
$tarjeta = $detalleDeposito['tarjeta'];
$nombre = $detalleDeposito['nombre'];

// Limpiar los detalles del depósito de la sesión (opcional)
unset($_SESSION['detalle_deposito']);
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <title><?php echo $translations['deposit_details']; ?></title>
    <link rel="stylesheet" href="../css/detalleDeposito.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['deposit_details']; ?></h1>
        <div class="details">
            <p><strong><?php echo $translations['date_and_time']; ?>:</strong> <?php echo $fecha; ?></p>
            <p><strong><?php echo $translations['amount_deposited']; ?>:</strong> <?php echo number_format($monto, 2); ?> BOB</p>
            <p><strong><?php echo $translations['card']; ?>:</strong> **** **** **** <?php echo substr($tarjeta, -4); ?></p>
            <p><strong><?php echo $translations['name']; ?>:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        </div>

        <!-- Pregunta para otro servicio -->
        <div class="fin-retiro">
            <h2><?php echo $translations['another_service_question']; ?></h2>
            <form method="post" action="procesarRespuesta.php"> <!-- Enviar a un script PHP -->
                <button type="submit" name="opcion" value="si"><?php echo $translations['yes']; ?></button>
                <button type="submit" name="opcion" value="no"><?php echo $translations['no']; ?></button>
            </form>
        </div>
    </div>
</body>

</html>