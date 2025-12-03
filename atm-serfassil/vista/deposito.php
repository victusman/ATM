<?php
session_start();

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
    if (isset($_POST['cancel'])) {
        header('Location: login.php');
        exit;
    }

    $amount = floatval($_POST['amount']);

    // Validar que el monto sea mayor que cero y múltiplo de 10
    if ($amount <= 0) {
        echo $translations['amount_greater_than_zero'];
    } elseif ($amount % 10 != 0) {
        echo $translations['amount_multiple_of_ten'];
    } else {
        require_once '../control/contenedorTransac.php';
        $transaction = new TransactionController();
        $result = $transaction->deposito($_POST['amount'], $_POST['accountId']);

        if ($result['success']) {
            $_SESSION['detalle_deposito'] = [
                'fecha' => date("d-m-Y"),
                'monto' => $_POST['amount'],
                'tarjeta' => $_SESSION['numbercard'],
                'nombre' => $_SESSION['user']
            ];
            // Redirigir a detalleDeposito.php dentro de la carpeta vista
            header('Location: ../vista/detalleDeposito.php');
            exit;
        } else {
            echo $result['message'];
        }
    }
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
    <meta charset="UTF-8">
    <title><?php echo $translations['deposit_money']; ?></title>
    <link rel="stylesheet" href="../css/deposito.css"> <!-- Vincula el archivo CSS -->
    <script>
        function validarMonto() {
            const monto = document.getElementById('amount').value;
            if (monto % 10 !== 0) {
                alert("<?php echo $translations['amount_multiple_of_ten']; ?>");
                return false; // Evita que el formulario se envíe
            }
            return true; // Permite que el formulario se envíe
        }

        function handleCancel() {
            document.getElementById('amount').removeAttribute('required');
            document.getElementById('cancelButton').form.submit();
        }
    </script>
</head>

<body>
    <div class="container">
        <h1><?php echo $translations['deposit_money']; ?></h1>
        <form method="post" onsubmit="return validarMonto()">
            <label for="amount"><?php echo $translations['amount_to_deposit']; ?>:</label>
            <select name="accountId">
                <?php foreach ($accounts as $acount): ?>
                    <option value="<?= $acount['id'] ?>">00000<?= $acount['id'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" id="amount" name="amount" step="0.01" required><br>
            <div class="buttons">
                <input type="submit" name="accept" value="<?php echo $translations['deposit']; ?>" class="btn depositar">
                <button type="submit" name="cancel" class="btn cancelar"><?php echo $translations['cancel']; ?></button>
            </div>
        </form>
    </div>
</body>

</html>