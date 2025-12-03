<?php
session_start();
require_once '../redirigir.php';

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');

// Verifica que la sesión tenga los datos necesarios
if (!isset($_SESSION['token'])) {
    echo "Error: Información de la sesión no encontrada. Regrese e inicie una nueva transacción.";
    exit;
}

// Establecer el idioma predeterminado (español)
$defaultLanguage = 'es';

// Obtener el idioma de la sesión (si está definido)
$language = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

// Cargar el archivo de idioma correspondiente
$translations = include __DIR__ . '/../idioma/' . $language . '.php';

$card_number = $_SESSION['numbercard']; // Se asume que el ID es el número de tarjeta
$name = $_SESSION['user'];
$amount = $_SESSION['retirar'];
$denominations = $_SESSION['denominations'];

// Obtener la fecha y hora actual en La Paz, Bolivia
$fechaActual = date("Y-m-d");
$horaActual = date("H:i:s");
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['withdrawal_detail']; ?></title>
    <link rel="stylesheet" href="../css/detalleRetiro.css">
</head>

<body>
    <div class="container">
        <!-- Columna izquierda: Detalles del retiro -->
        <div class="left-column">
            <h1><?php echo $translations['withdrawal_detail']; ?></h1>
            <p><?php echo $translations['date']; ?>: <?php echo $fechaActual; ?> &nbsp;&nbsp;&nbsp; <?php echo $translations['time']; ?>: <?php echo $horaActual; ?></p>

            <p><?php echo $translations['location']; ?>: <strong>UNIVERSIDAD PRIVADA DOMINGO SAVIO</strong></p>
            <p>Santa Cruz, BOLIVIA</p>

            <h2><?php echo $translations['withdrawal']; ?></h2>
            <p><?php echo $translations['card']; ?>: **** **** **** <?php echo substr($card_number, -4); ?></p>
            <p><?php echo $translations['name']; ?>: <?php echo htmlspecialchars($name); ?></p>
            <p><?php echo $translations['amount']; ?>: <?php echo number_format($amount, 2); ?> Bs</p>

            <h3><?php echo $translations['currency_bill_quantity']; ?></h3>
            <p>
                <?php
                foreach ($denominations as $denom) {
                    echo "{$denom['moneda']} - {$denom['billete']} - {$denom['cantidad']}<br>";
                }
                ?>
            </p>

           
        </div>

        <!-- Columna derecha: Preguntas y respuestas -->
        <div class="right-column">
            <p><?php echo $translations['save_planet']; ?></p>
            <h2><?php echo $translations['print_receipt']; ?></h2>
            <form method="post" action="pdfRetiro.php">
                <button type="submit" name="respuesta" value="si"><?php echo $translations['yes']; ?></button>
                <button type="submit" name="respuesta" value="no"><?php echo $translations['no']; ?></button>
            </form>

            <h2><?php echo $translations['another_service']; ?></h2>
            <form method="post" action="procesarRespuesta.php"> <!-- Enviar a un script PHP -->
                <button type="submit" name="opcion" value="si"><?php echo $translations['yes']; ?></button>
                <button type="submit" name="opcion" value="no"><?php echo $translations['no']; ?></button>
            </form>
        </div>
    </div>
</body>

</html>