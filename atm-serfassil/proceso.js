function redirigir(url, delay = 3000, tipo = 'general') {
    console.log('Iniciando redirección a', url); // Mensaje de depuración
    
    // Determinar el mensaje según el tipo de transacción
    let mensaje, descripcion, iconos, colorClass;
    switch(tipo) {
        case 'retiro':
            mensaje = 'Procesando Retiro';
            descripcion = 'Contando billetes y actualizando su saldo...';
            iconos = '💳';
            colorClass = 'modal-withdrawal';
            break;
        case 'deposito':
            mensaje = 'Procesando Depósito';
            descripcion = 'Validando billetes y actualizando su cuenta...';
            iconos = '💰';
            colorClass = 'modal-deposit';
            break;
        case 'consulta':
            mensaje = 'Consultando Saldo';
            descripcion = 'Obteniendo información de su cuenta...';
            iconos = '📊';
            colorClass = 'modal-balance';
            break;
        default:
            mensaje = 'Procesando';
            descripcion = 'Por favor espera unos momentos...';
            iconos = '⚙️';
            colorClass = 'modal-general';
    }
    
    // Crear el overlay modal
    const overlay = document.createElement('div');
    overlay.id = 'processing-overlay';
    overlay.innerHTML = `
        <div class="processing-modal ${colorClass}">
            <div class="modal-content">
                <div class="spinner-container">
                    <div class="modern-spinner"></div>
                    <div class="spinner-icon">${iconos}</div>
                </div>
                <h1 class="processing-title">${mensaje}</h1>
                <p class="processing-description">${descripcion}</p>
                <div class="progress-wrapper">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">Procesando<span class="loading-dots"></span></div>
                </div>
            </div>
        </div>
    `;
    
    // Agregar estilos CSS directamente con !important para forzar estilos
    const styles = document.createElement('style');
    styles.textContent = `
        #processing-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(0, 0, 0, 0.85) !important;
            backdrop-filter: blur(8px) !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            z-index: 999999 !important;
            animation: fadeIn 0.3s ease-out !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        
        .processing-modal {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%) !important;
            backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 25px !important;
            padding: 50px 40px !important;
            text-align: center !important;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4) !important;
            animation: modalSlideIn 0.5s ease-out !important;
            max-width: 450px !important;
            width: 90% !important;
            position: relative !important;
            overflow: hidden !important;
            margin: 20px !important;
        }
        
        .modal-withdrawal {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
        }
        
        .modal-deposit {
            background: linear-gradient(135deg, rgba(86, 171, 47, 0.9) 0%, rgba(168, 230, 207, 0.9) 100%);
        }
        
        .modal-balance {
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.9) 0%, rgba(245, 87, 108, 0.9) 100%);
        }
        
        .modal-general {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(5, 5, 5, 0.9) 100%);
        }
        
        .processing-modal::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: particleFloat 15s linear infinite;
            pointer-events: none;
        }
        
        .spinner-container {
            position: relative;
            margin-bottom: 30px;
        }
        
        .modern-spinner {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #ffffff;
            border-right: 4px solid rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: modernSpin 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            margin: 0 auto;
            position: relative;
        }
        
        .modern-spinner::after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 2px solid transparent;
            border-top: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: modernSpin 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite reverse;
        }
        
        .spinner-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            animation: iconPulse 2s ease-in-out infinite;
        }
        
        .processing-title {
            color: #ffffff !important;
            font-size: 2rem !important;
            font-weight: 300 !important;
            margin-bottom: 15px !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            line-height: 1.2 !important;
        }
        
        .processing-description {
            color: rgba(255, 255, 255, 0.95) !important;
            font-size: 1.1rem !important;
            margin-bottom: 30px !important;
            line-height: 1.4 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        
        .progress-wrapper {
            margin-top: 25px;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #64b5f6, #42a5f5, #1e88e5);
            border-radius: 4px;
            animation: progressFill 3s ease-in-out infinite;
            box-shadow: 0 2px 10px rgba(100, 181, 246, 0.4);
        }
        
        .progress-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes modernSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }
        
        @keyframes progressFill {
            0% { width: 0%; }
            30% { width: 30%; }
            60% { width: 70%; }
            100% { width: 100%; }
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
        
        @keyframes particleFloat {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-30px, -30px) rotate(360deg); }
        }
        
        @media (max-width: 480px) {
            .processing-modal {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .processing-title {
                font-size: 1.6rem;
            }
            
            .processing-description {
                font-size: 1rem;
            }
            
            .modern-spinner {
                width: 60px;
                height: 60px;
            }
            
            .spinner-icon {
                font-size: 1.5rem;
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    `;
    
    // Limpiar cualquier overlay previo
    const existingOverlay = document.getElementById('processing-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Agregar estilos y modal al documento
    document.head.appendChild(styles);
    document.body.appendChild(overlay);
    
    // Prevenir scroll del body y forzar visibilidad
    document.body.style.overflow = 'hidden';
    overlay.style.display = 'flex';
    
    // Debug: Log para verificar que el modal se creó
    console.log('Modal de procesamiento creado:', overlay);
    
    // Redirigir después del delay
    setTimeout(() => {
        // Animación de salida
        overlay.style.animation = 'fadeIn 0.3s ease-out reverse';
        setTimeout(() => {
            document.body.style.overflow = '';
            window.location.href = url;
        }, 300);
    }, delay);
}

// Funciones específicas para cada tipo de transacción
function redirigirRetiro(url, delay = 3000) {
    redirigir(url, delay, 'retiro');
}

function redirigirDeposito(url, delay = 3000) {
    redirigir(url, delay, 'deposito');
}

function redirigirConsulta(url, delay = 3000) {
    redirigir(url, delay, 'consulta');
}
