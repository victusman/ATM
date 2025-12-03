<?php
session_start();

// Verificar si el usuario está logueado
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

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customAmount = $_POST['custom_amount'];

    // Validar que el monto sea un número, mayor que 0 y múltiplo de 10
    if (is_numeric($customAmount) && $customAmount > 0 && $customAmount % 10 === 0) {
        require_once '../control/contenedorTransac.php';
        $transaction = new TransactionController();

        // Obtener el ID del usuario desde la sesión
        $userId = $_POST['accountId'];

        // Llamar al método retiro con dos argumentos: el monto y el ID del usuario
        $result = $transaction->retiro($customAmount, $userId);

        // Verificar si la transacción fue exitosa
        if ($result['success']) {
            // Guardar el monto retirado en la sesión
            $_SESSION['retirar'] = $customAmount;

            // Calcular las denominaciones
            $denominations = calcularDenominaciones($customAmount);
            $_SESSION['denominations'] = $denominations;

            // Redirigir a la página de detalle del retiro
            header('Location: ../vista/detalleRetiro.php');
            exit;
        } else {
            $errorMessage = $result['message'];
        }
    } else {
        $errorMessage = $translations['invalid_amount_message'];
    }
}

// Función para calcular las denominaciones de billetes
function calcularDenominaciones($amount)
{
    $denominations = [
        100 => 0,
        50 => 0,
        20 => 0,
        10 => 0
    ];

    foreach ($denominations as $bill => &$count) {
        if ($amount >= $bill) {
            $count = intdiv($amount, $bill);
            $amount = $amount % $bill;
        }
    }

    // Filtrar solo las denominaciones utilizadas
    $denominations = array_filter($denominations, function ($count) {
        return $count > 0;
    });

    // Convertir a formato deseado
    $result = [];
    foreach ($denominations as $bill => $count) {
        $result[] = ['moneda' => 'Bs', 'billete' => $bill, 'cantidad' => $count];
    }

    return $result;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <title><?php echo $translations['custom_amount_title']; ?></title>
    <link rel="stylesheet" href="../css/ingresarMonto.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['custom_amount_title']; ?></h1>
        <form method="post">
            <input type="hidden" name="accountId" value="<?= $_GET['accountId'] ?? 1 ?>">
            <label for="custom_amount"><?php echo $translations['amount']; ?>:</label>
            <input type="text" id="custom_amount" name="custom_amount" required>
            <button type="submit"><?php echo $translations['confirm']; ?></button>
        </form>
        <?php
        // Mostrar mensaje de error si existe
        if (isset($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
    </div>
</body>

</html>