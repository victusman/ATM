<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Incluir el controlador de transacciones
require_once '../control/contenedorTransac.php';

// Crear una instancia del controlador
$transactionController = new TransactionController();

// Obtener el saldo del usuario actual
$saldoResult = $transactionController->consultarSaldo();

if (!$saldoResult['success']) {
    echo "Error: " . $saldoResult['message'];
    exit;
}

// Simular la estructura de accounts para mantener compatibilidad
$accounts = [
    [
        'id' => $_SESSION['current_user'],
        'currentBalance' => $saldoResult['balance']
    ]
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Saldo</title>
    <link rel="stylesheet" href="../css/consulta.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <?php foreach ($accounts as $account): ?>
            <h1>Saldo Actual de cuenta con n <?= $account['id'] ?></h1>
            <p>Su saldo es: <?php echo number_format($account['currentBalance'], 2); ?> BOB</p>
            <form method="post" action="pdfsaldo.php">
                <input type="hidden" name='currentBalance' value="<?= $account['currentBalance'] ?>">
                <input type="hidden" name='id' value="<?= $account['id'] ?>">
                <input type="submit" value="Imprimir Saldo en PDF" class="btn">
            </form>
        <?php endforeach; ?>
        <!-- Pregunta para otro servicio -->
        <div class="fin-retiro">
            <h2>¿Deseas realizar otro servicio?</h2>
            <form method="post" action="procesarRespuesta.php"> <!-- Enviar a un script PHP -->
                <button type="submit" name="opcion" value="si">SI</button>
                <button type="submit" name="opcion" value="no">NO</button>
            </form>
        </div>


    </div>
</body>

</html>