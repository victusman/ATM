<?php
session_start();

// Datos de usuarios simulados (en lugar de una base de datos)
$users = [
    '1234567890' => [
        'name' => 'Juan Perez',
        'pin' => '1234',
        'balance' => 10000.00, // Saldo en BOB
        'transactions' => []   // Historial de transacciones
    ],
    '0987654321' => [
        'name' => 'Maria Lopez',
        'pin' => '4321',
        'balance' => 5000.00,
        'transactions' => []
    ]
];

// Guardar los usuarios en la sesión para acceder desde otros archivos
$_SESSION['users'] = $users;

// Redirigir al login (se supone que el archivo login.php se encarga de autenticación)
header('Location: vista/login.php');
exit;
?>
