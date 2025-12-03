<?php
session_start();

// Verifica que la sesión tenga los datos necesarios
if (!isset($_SESSION['current_user'], $_SESSION['users'], $_SESSION['retirar'])) {
    echo "Error: Información de la sesión no encontrada. Regrese e inicie una nueva transacción.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fin del Retiro</title>
</head>
<body>
    <h2>¿Deseas realizar otro servicio ?</h2>
    <form method="post" action="login.php">
        <button type="submit" name="opcion" value="si">SI</button>
        <button type="submit" name="opcion" value="no">NO</button>
    </form>
</body>
</html>
