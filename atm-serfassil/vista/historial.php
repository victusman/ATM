<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener las fechas seleccionadas del formulario
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

// Obtener la cuenta seleccionada
$accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
// Obtener las cuentas disponibles localmente
$accounts = [
    [
        'id' => $_SESSION['current_user'],
        'currentBalance' => $_SESSION['users'][$_SESSION['current_user']]['balance']
    ]
];

// Determinar el ID de la cuenta por defecto si no está seleccionada
if (!$accountId && !empty($accounts)) {
    $accountId = $accounts[0]['id'];
}

// Obtener transacciones localmente
$transactions = [];
if ($accountId && isset($_SESSION['users'][$accountId])) {
    $userTransactions = $_SESSION['users'][$accountId]['transactions'];
    
    // Filtrar por fechas si están especificadas
    if ($fecha_inicio && $fecha_fin) {
        $transactions = array_filter($userTransactions, function($transaction) use ($fecha_inicio, $fecha_fin) {
            $transactionDate = date('Y-m-d', strtotime($transaction['date']));
            return $transactionDate >= $fecha_inicio && $transactionDate <= $fecha_fin;
        });
    } else {
        $transactions = $userTransactions;
    }
    
    // Convertir al formato esperado por la vista
    $transactions = array_map(function($transaction) {
        return [
            'commentSystem' => ucfirst($transaction['type']),
            'amount' => $transaction['amount'],
            'created_at' => $transaction['date']
        ];
    }, $transactions);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Transacciones</title>
    <link rel="stylesheet" href="../css/historial.css">
</head>

<body>
    <div class="container">
        <h1>Historial de Transacciones</h1>

        <div class="scrollable-section">
            <!-- Formulario para seleccionar cuenta y rango de fechas -->
            <form method="post" action="historial.php">
                <label for="accountId">Seleccionar Cuenta:</label>
                <select name="accountId" id="accountId" required>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?= htmlspecialchars($account['id']) ?>" <?= ($account['id'] == $accountId) ? 'selected' : '' ?>>
                            <?= '00000' . htmlspecialchars($account['id']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">

                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">

                <button type="submit">Ver Historial</button>
            </form>

            <br>

            <!-- Opción para mostrar transacciones del día -->
            <form method="post" action="historial.php">
                <input type="hidden" name="accountId" value="<?= htmlspecialchars($accountId) ?>">
                <input type="hidden" name="fecha_inicio" value="<?= date('Y-m-d') ?>">
                <input type="hidden" name="fecha_fin" value="<?= date('Y-m-d') ?>">
                <button type="submit">Ver Transacciones del Día</button>
            </form>

            <br>

            <!-- Mostrar transacciones -->
            <?php if (empty($transactions)): ?>
                <p class="no-transactions">No hay transacciones registradas.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaction['commentSystem']) ?></td>
                            <td><?= htmlspecialchars($transaction['amount']) ?> BOB</td>
                            <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div class="print-section">
            <h2>Imprimir Historial</h2>
            <form method="post" action="pdfHistorial.php">
                <input type="hidden" name="accountId" value="<?= htmlspecialchars($accountId) ?>">
                <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
                <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
                <button type="submit">Imprimir</button>
            </form>
        </div>

        <div class="print-section">
            <h2>¿Deseas realizar otro servicio?</h2>
            <form method="post" action="procesarRespuesta.php" class="service-buttons">
                <button type="submit" name="opcion" value="si">SI</button>
                <button type="submit" name="opcion" value="no">NO</button>
            </form>
        </div>
    </div>
</body>

</html>
