function redirigir(url, delay = 3000) {
    console.log('Iniciando redirección a', url); // Mensaje de depuración
    
    // Muestra el mensaje de "Procesando"
    document.body.innerHTML = `
        <div class="center">
            <h1>Procesando..</h1>
            <p>Por favor espera unos momentos.</p>
        </div>
    `;
    
    // Estilos para centrar el mensaje en la pantalla
    const style = document.createElement('style');
    style.textContent = `
        .center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
    `;
    document.head.append(style);
    
    // Redirige después del retraso especificado
    setTimeout(() => {
        window.location.href = url;
    }, delay);
}
