<?php
session_start();
require('../fpdf186/fpdf.php'); // Asegúrate de que la ruta a la biblioteca FPDF sea correcta

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

// Variable para almacenar el mensaje
$mensaje = "";

// Procesa la respuesta del formulario de impresión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta'])) {
    if ($_POST['respuesta'] === 'si') {
        $current_user_id = $_SESSION['numbercard'];
        $card_number = $current_user_id; // Se asume que el ID es el número de tarjeta
        $name = $_SESSION['user'];
        $amount = $_SESSION['retirar'];

        // Crear un nuevo PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Agregar contenido al PDF
        $pdf->Cell(0, 10, $translations['receipt_title'], 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);

        // Obtener la fecha y hora actual en La Paz, Bolivia
        $fechaActual = date("Y-m-d");
        $horaActual = date("H:i:s");

        $pdf->Cell(0, 10, $translations['date'] . ': ' . $fechaActual . ' ' . $translations['time'] . ': ' . $horaActual, 0, 1);
        $pdf->Cell(0, 10, $translations['location'] . ': UNIVERSIDAD PRIVADA DOMINGO SAVIO', 0, 1);
        $pdf->Cell(0, 10, 'Santa Cruz, BOLIVIA', 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, $translations['card'] . ': **** **** **** ' . substr($card_number, -4), 0, 1);
        $pdf->Cell(0, 10, $translations['name'] . ': ' . htmlspecialchars($name), 0, 1);
        $pdf->Cell(0, 10, $translations['amount'] . ': ' . number_format($amount, 2) . ' Bs', 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, $translations['save_planet'], 0, 1);

        // Guardar el PDF en un archivo y mostrarlo al usuario
        $pdf->Output('D', 'Comprobante_Retiro.pdf');

        // Aquí podrías agregar la redirección, pero vamos a mostrar la pregunta de todos modos.
        // header('Location: finRetiro.php');
        // exit;
    } elseif ($_POST['respuesta'] === 'no') {
        $mensaje = "<p class='mensaje-error'>{$translations['receipt_not_printed']}</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <title><?php echo $translations['end_withdrawal']; ?></title>
    <link rel="stylesheet" href="../css/pdfRetiro.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['end_withdrawal']; ?></h1>

        <!-- Mostrar el mensaje de error en la parte superior -->
        <?php if (!empty($mensaje)): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>

        <div class="message">
            <p><?php echo $translations['another_service_question']; ?></p>
        </div>
        <div class="buttons">
            <form method="post" action="procesarRespuesta.php"> <!-- Enviar a un script PHP -->
                <button type="submit" name="opcion" value="si"><?php echo $translations['yes']; ?></button>
                <button type="submit" name="opcion" value="no"><?php echo $translations['no']; ?></button>
            </form>
        </div>
    </div>
</body>

</html>