<?php
// Desactivar mensajes de advertencia y errores temporalmente
error_reporting(0);

session_start();
require('../fpdf186/fpdf.php'); // Asegúrate de que la ruta a la biblioteca FPDF sea correcta

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');

// Verificar si el usuario está logueado
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Obtener datos del usuario desde la sesión

// Verificar si las claves 'name' y 'balance' existen en $userData
$nombreCliente = $_SESSION['user'] ?? 'Nombre no disponible';
$saldoDisponible = $_POST['currentBalance'] ?? 0.00;
$currentUser = $_POST['id'] ?? 'Desconocido';
// Generar PDF
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear un nuevo PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Agregar contenido al PDF
    $pdf->Cell(0, 10, 'Consulta de Saldo', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Número de Cuenta: ' . htmlspecialchars($currentUser), 0, 1);
    $pdf->Cell(0, 10, 'Nombre del Cliente: ' . htmlspecialchars($nombreCliente), 0, 1);
    $pdf->Cell(0, 10, 'Saldo Disponible: ' . number_format($saldoDisponible, 2) . ' BOB', 0, 1);
    $pdf->Ln(10);

    // Obtener la fecha y hora actual en La Paz, Bolivia
    $fechaHoraActual = date("Y-m-d H:i:s");
    $pdf->Cell(0, 10, 'Fecha y Hora: ' . $fechaHoraActual, 0, 1);

    // Guardar el PDF en un archivo y mostrarlo al usuario
    $pdf->Output('D', 'Consulta_Saldo.pdf');
    exit;
}
