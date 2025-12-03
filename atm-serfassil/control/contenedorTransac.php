<?php
// Inicia la sesión solo si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function dd(...$params) {
    die(var_dump($params));
}

class TransactionController
{
    public function retiro($amount, $idCuenta)
    {
        // Asegurarse de que el monto sea un número flotante
        $amount = (float)$amount;
        $originalAmount = $amount;

        // Verificar que $idCuenta y $originalAmount sean válidos
        if (empty($idCuenta) || empty($originalAmount)) {
            return [
                'success' => false,
                'message' => 'Datos inválidos para la solicitud.'
            ];
        }

        // Verificar que el token de sesión esté definido
        if (!isset($_SESSION['token'])) {
            return [
                'success' => false,
                'message' => 'Token de sesión no válido o no definido.'
            ];
        }

        // Verificar que el usuario actual esté definido
        if (!isset($_SESSION['current_user']) || !isset($_SESSION['users'][$_SESSION['current_user']])) {
            return [
                'success' => false,
                'message' => 'Usuario no válido.'
            ];
        }

        $currentUser = $_SESSION['current_user'];
        $user = $_SESSION['users'][$currentUser];

        // Verificar que hay suficiente saldo
        if ($user['balance'] < $originalAmount) {
            return [
                'success' => false,
                'message' => 'Saldo insuficiente. Saldo actual: ' . number_format($user['balance'], 2) . ' BOB'
            ];
        }

        // Billetes disponibles para dispensar
        $bills = [200, 100, 50, 20, 10];
        $dispensedBills = [];
        $tempAmount = $amount;

        // Calcular los billetes que se pueden dispensar
        foreach ($bills as $bill) {
            while ($tempAmount >= $bill) {
                $dispensedBills[] = $bill;
                $tempAmount -= $bill;
            }
        }

        // Si no se puede dispensar el monto exacto
        if ($tempAmount > 0) {
            return [
                'success' => false,
                'message' => 'No se puede dispensar el monto exacto con los billetes disponibles.'
            ];
        }

        // Realizar el retiro
        $_SESSION['users'][$currentUser]['balance'] -= $originalAmount;
        
        // Agregar la transacción al historial
        $transaction = [
            'type' => 'retiro',
            'amount' => $originalAmount,
            'date' => date('Y-m-d H:i:s'),
            'bills' => $dispensedBills
        ];
        $_SESSION['users'][$currentUser]['transactions'][] = $transaction;

        return [
            'success' => true,
            'message' => "Retiro exitoso. Billetes dispensados: " . implode(', ', $dispensedBills),
            'new_balance' => $_SESSION['users'][$currentUser]['balance'],
            'bills' => $dispensedBills
        ];
    }public function deposito($amount, $idCuenta)
    {
        // Asegurarse de que el monto sea un número flotante
        $amount = (float)$amount;
        $originalAmount = $amount;

        // Verificar que $idCuenta y $originalAmount sean válidos
        if (empty($idCuenta) || empty($originalAmount) || $originalAmount <= 0) {
            return [
                'success' => false,
                'message' => 'Datos inválidos para la solicitud.'
            ];
        }

        // Verificar que el token de sesión esté definido
        if (!isset($_SESSION['token'])) {
            return [
                'success' => false,
                'message' => 'Token de sesión no válido o no definido.'
            ];
        }

        // Verificar que el usuario actual esté definido
        if (!isset($_SESSION['current_user']) || !isset($_SESSION['users'][$_SESSION['current_user']])) {
            return [
                'success' => false,
                'message' => 'Usuario no válido.'
            ];
        }

        $currentUser = $_SESSION['current_user'];

        // Realizar el depósito
        $_SESSION['users'][$currentUser]['balance'] += $originalAmount;
        
        // Agregar la transacción al historial
        $transaction = [
            'type' => 'deposito',
            'amount' => $originalAmount,
            'date' => date('Y-m-d H:i:s')
        ];
        $_SESSION['users'][$currentUser]['transactions'][] = $transaction;

        return [
            'success' => true,
            'message' => "Depósito exitoso por " . number_format($originalAmount, 2) . " BOB",
            'new_balance' => $_SESSION['users'][$currentUser]['balance']
        ];
    }

    public function consultarSaldo()
    {
        // Verificar que el token de sesión esté definido
        if (!isset($_SESSION['token'])) {
            return [
                'success' => false,
                'message' => 'Token de sesión no válido o no definido.'
            ];
        }

        // Verificar que el usuario actual esté definido
        if (!isset($_SESSION['current_user']) || !isset($_SESSION['users'][$_SESSION['current_user']])) {
            return [
                'success' => false,
                'message' => 'Usuario no válido.'
            ];
        }

        $currentUser = $_SESSION['current_user'];
        $user = $_SESSION['users'][$currentUser];

        return [
            'success' => true,
            'balance' => $user['balance'],
            'name' => $user['name'],
            'card_number' => $currentUser
        ];
    }

    public function obtenerHistorial()
    {
        // Verificar que el token de sesión esté definido
        if (!isset($_SESSION['token'])) {
            return [
                'success' => false,
                'message' => 'Token de sesión no válido o no definido.'
            ];
        }

        // Verificar que el usuario actual esté definido
        if (!isset($_SESSION['current_user']) || !isset($_SESSION['users'][$_SESSION['current_user']])) {
            return [
                'success' => false,
                'message' => 'Usuario no válido.'
            ];
        }

        $currentUser = $_SESSION['current_user'];
        $user = $_SESSION['users'][$currentUser];

        return [
            'success' => true,
            'transactions' => array_reverse($user['transactions']) // Mostrar las más recientes primero
        ];
    }
}