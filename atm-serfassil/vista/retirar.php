<?php
session_start();
require_once '../redirigir.php';

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['amount'] === 'cancel') {
        echo '<script>
                alert("' . $translations['transaction_cancelled'] . '");
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 2000); // Redirigir después de 2 segundos
              </script>';
        exit;
    } elseif ($_POST['amount'] === 'other') {
        header('Location: ingresarMonto.php?accountId=' . $_POST['accountId']); // Redirige a una página para ingresar un monto personalizado.
        exit;
    }

    // Procesamos la transacción
    require_once '../control/contenedorTransac.php';
    $transaction = new TransactionController();
    $result = $transaction->retiro($_POST['amount'], $_POST['accountId']);

    if ($result['success']) {
        // Guardamos el monto retirado en la sesión para usarlo en el comprobante
        $_SESSION['retirar'] = $_POST['amount'];

        // Calcular las denominaciones
        $amount = $_POST['amount'];
        $denominations = calcularDenominaciones($amount);
        $_SESSION['denominations'] = $denominations;

        // Guardar la transacción en el historial (solo si no existe ya)
        $currentUser = $_SESSION['current_user'];
        $userData = $_SESSION['users'][$currentUser];

        // Verificar si ya existe una transacción idéntica
        $transaccionExistente = false;
        foreach ($userData['transactions'] as $transaccion) {
            if ($transaccion['type'] === 'Retiro' && $transaccion['amount'] == $amount && $transaccion['date'] == date("Y-m-d H:i:s")) {
                $transaccionExistente = true;
                break;
            }
        }

        // Si no existe, agregar la transacción al historial
        if (!$transaccionExistente) {
            $userData['transactions'][] = [
                'type' => 'Retiro', // Asegúrate de que el tipo sea "Retiro"
                'amount' => $amount,
                'date' => date("Y-m-d H:i:s") // Fecha y hora actual en La Paz
            ];
            $_SESSION['users'][$currentUser] = $userData;
        }

        // Redirigir a proceso.php para mostrar la pantalla de "Procesando" antes de detalleRetiro.php
        redirigir('../vista/detalleRetiro.php');
    } else {
        // Si ocurre un error en la transacción, mostramos el mensaje de error.
        echo $result['message'];
    }
}

function calcularDenominaciones($amount)
{
    $denominations = [
        100 => 0,
        50 => 0,
        20 => 0,
        10 => 0,
        5 => 0,
        2 => 0,
        1 => 0
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

// Obtener la cuenta del usuario actual localmente
$accounts = [
    [
        'id' => $_SESSION['current_user'],
        'currentBalance' => $_SESSION['users'][$_SESSION['current_user']]['balance']
    ]
];
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <title><?php echo $translations['withdraw_money']; ?></title>
    <link rel="stylesheet" href="../css/retirar.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['withdraw_money']; ?></h1>
        <form method="post">
            <p><?php echo $translations['select_amount']; ?></p>
            <select name="accountId">
                <?php foreach ($accounts as $acount): ?>
                    <option value="<?= $acount['id'] ?>">00000<?= $acount['id'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="button-grid">
                <button type="submit" name="amount" value="900">900 bs</button>
                <button type="submit" name="amount" value="600">600 bs</button>
                <button type="submit" name="amount" value="400">400 bs</button>
                <button type="submit" name="amount" value="100">100 bs</button>
                <button type="submit" name="amount" value="50">50 bs</button>
                <button type="submit" name="amount" value="20">20 bs</button>
                <button type="submit" name="amount" value="10">10 bs</button>
            </div>
            <button type="submit" name="amount" value="other"><?php echo $translations['other_amount']; ?></button>
            <button type="submit" name="amount" value="cancel"><?php echo $translations['cancel']; ?></button>
        </form>
        <div class="footer">
            <p><?php echo $translations['footer_text']; ?></p>
        </div>
    </div>
</body>

</html>