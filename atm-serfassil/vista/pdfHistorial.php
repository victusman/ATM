<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');

// Verificar si se enviaron las fechas en el formulario
if (!isset($_POST['fecha_inicio']) || !isset($_POST['fecha_fin'])) {
    die("Por favor, seleccione un rango de fechas.");
}

// Obtener el rango de fechas del formulario
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$accountId = $_POST['accountId'];


// Obtener transacciones según las fechas y la cuenta seleccionada
$transactions = [];
if ($accountId) {
    
    $url = 'https://4-co.de/upds/banco-fassil/api/transactions?idAccount=' . $accountId;
    if ($fecha_inicio && $fecha_fin) {
        $url .= '&from=' . $fecha_inicio . '&to=' . $fecha_fin;
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'X-Mi-Token: Bearer ' . $_SESSION['token']
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    if ($response !== false) {
        $apiTransactions = json_decode($response, true);
        $transactions = $apiTransactions['data']['accounts'][0]['transactions'] ?? [];
    }
}
// Verificar si hay transacciones en el rango de fechas
if (empty($transactions)) {
    die("No hay transacciones en el rango de fechas seleccionado.");
}

// Incluir la librería FPDF
require('../fpdf186/fpdf.php'); // Asegúrate de que la ruta a FPDF sea correcta

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título del PDF
$pdf->Cell(0, 10, 'Historial de Transacciones', 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Tipo', 1, 0, 'C');
$pdf->Cell(60, 10, 'Monto', 1, 0, 'C');
$pdf->Cell(60, 10, 'Fecha', 1, 1, 'C');

// Contenido de la tabla
$pdf->SetFont('Arial', '', 12);
foreach ($transactions as $transaction) {
    $pdf->Cell(60, 10, $transaction['commentSystem'], 1, 0, 'C');
    $pdf->Cell(60, 10, $transaction['amount'] . ' BOB', 1, 0, 'C');
    $pdf->Cell(60, 10, $transaction['created_at'], 1, 1, 'C');
}

// Salida del PDF
$pdf->Output('D', 'historial_transacciones.pdf'); // Descargar el PDF con el nombre "historial_transacciones.pdf"
?>