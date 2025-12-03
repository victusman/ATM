<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Transacción</title>
    <link rel="stylesheet" href="../css/proceso_modal.css">
</head>
<body>
    <?php
    // Obtener parámetros de la URL
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'general';
    $destino = isset($_GET['destino']) ? $_GET['destino'] : '../vista/menu.php';
    $delay = isset($_GET['delay']) ? (int)$_GET['delay'] : 3000;
    
    // Configurar mensaje según el tipo
    switch($tipo) {
        case 'retiro':
            $mensaje = 'Procesando Retiro';
            $descripcion = 'Contando billetes y actualizando su saldo...';
            $icono = '💳';
            $clase_modal = 'modal-withdrawal';
            break;
        case 'deposito':
            $mensaje = 'Procesando Depósito';
            $descripcion = 'Validando billetes y actualizando su cuenta...';
            $icono = '💰';
            $clase_modal = 'modal-deposit';
            break;
        case 'consulta':
            $mensaje = 'Consultando Saldo';
            $descripcion = 'Obteniendo información de su cuenta...';
            $icono = '📊';
            $clase_modal = 'modal-balance';
            break;
        default:
            $mensaje = 'Procesando';
            $descripcion = 'Por favor espere unos momentos...';
            $icono = '⚙️';
            $clase_modal = 'modal-general';
    }
    ?>

    <div class="processing-overlay">
        <div class="processing-modal <?php echo $clase_modal; ?>">
            <div class="modal-content">
                <div class="spinner-container">
                    <div class="modern-spinner"></div>
                    <div class="spinner-icon"><?php echo $icono; ?></div>
                </div>
                <h1 class="processing-title"><?php echo $mensaje; ?></h1>
                <p class="processing-description"><?php echo $descripcion; ?></p>
                <div class="progress-wrapper">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">Procesando<span class="loading-dots"></span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Redirigir después del tiempo especificado
        setTimeout(function() {
            // Animación de salida
            const overlay = document.querySelector('.processing-overlay');
            overlay.style.animation = 'fadeOut 0.5s ease-out forwards';
            
            setTimeout(function() {
                window.location.href = '<?php echo $destino; ?>';
            }, 500);
        }, <?php echo $delay; ?>);
        
        // Prevenir navegación hacia atrás
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</body>
</html>