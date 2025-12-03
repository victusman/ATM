<?php
session_start();

class AuthController {
    public function login($cardNumber, $pin) {
        // Verificar si el usuario existe y el PIN es correcto
        if (isset($_SESSION['users'][$cardNumber])) {
            $user = $_SESSION['users'][$cardNumber];
            if ($user['pin'] === $pin) {
                $_SESSION['current_user'] = $cardNumber; // Guardar el usuario actual en sesión
                return true;
            }
        }
        return false;
    }

    public function logout() {
        // Destruir la sesión
        session_destroy();
    }
}
?>