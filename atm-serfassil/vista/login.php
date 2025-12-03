<?php
session_start();

// Establecer el idioma predeterminado (español)
$defaultLanguage = 'es';

// Obtener el idioma de la sesión (si está definido)
$language = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

// Cargar el archivo de idioma correspondiente
$translations = include __DIR__ . '/../idioma/' . $language . '.php';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardNumber = $_POST['card_number'];
    $pin = $_POST['pin'];
    
    // Verificar si existen los datos de usuarios en la sesión
    if (!isset($_SESSION['users'])) {
        // Si no están en la sesión, cargar desde el índice
        $users = [
            '1234567890' => [
                'name' => 'Juan Perez',
                'pin' => '1234',
                'balance' => 10000.00,
                'transactions' => []
            ],
            '0987654321' => [
                'name' => 'Maria Lopez',
                'pin' => '4321',
                'balance' => 5000.00,
                'transactions' => []
            ]
        ];
        $_SESSION['users'] = $users;
    }
    
    // Verificar credenciales localmente
    if (isset($_SESSION['users'][$cardNumber])) {
        $user = $_SESSION['users'][$cardNumber];
        if ($user['pin'] === $pin) {
            // Login exitoso
            $_SESSION['token'] = 'local_token_' . $cardNumber; // Token simulado
            $_SESSION['user'] = $user['name'];
            $_SESSION['numbercard'] = $cardNumber;
            $_SESSION['current_user'] = $cardNumber;
            header('Location: menu.php');
            exit;
        } else {
            $error_message = "PIN incorrecto";
        }
    } else {
        $error_message = "Número de tarjeta no válido";
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <title><?php echo $translations['welcome']; ?></title>
    <link rel="stylesheet" href="../css/login.css"> <!-- Vincula el archivo CSS -->
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="../img/logoMain.png" alt="Logo del Banco">
        </div>
        <div class="form-container">
            <h1><?php echo $translations['welcome']; ?></h1>

            <!-- Formulario de selección de idioma -->
            <form action="../set_lenguage.php" method="post" id="languageForm" style="margin-bottom: 20px;">
                <label for="language"><?php echo $translations['select_language']; ?>:</label>
                <select name="language" id="language" onchange="this.form.submit()">
                    <option value="es" <?php echo ($language == 'es') ? 'selected' : ''; ?>>Español</option>
                    <option value="en" <?php echo ($language == 'en') ? 'selected' : ''; ?>>English</option>
                    <option value="pt" <?php echo ($language == 'pt') ? 'selected' : ''; ?>>Português</option>
                </select>
            </form>

            <!-- Mostrar mensaje de error si existe -->
            <?php if (isset($error_message)): ?>
                <div style="color: red; margin-bottom: 15px; text-align: center;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form method="post">
                <label for="card_number"><?php echo $translations['card_number']; ?>:</label>
                <input type="text" id="card_number" name="card_number" placeholder=" " required>
                <label for="pin"><?php echo $translations['pin']; ?>:</label>
                <input type="password" id="pin" name="pin" placeholder=" " required>
                <input type="submit" value="<?php echo $translations['login']; ?>">
            </form>
        </div>
    </div>
</body>

</html>