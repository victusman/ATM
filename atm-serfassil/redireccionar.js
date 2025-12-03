function mostrarMensajeYRedirigir(mensaje, url, tiempo) {
    // Mostrar el mensaje
    alert(mensaje);

    // Redirigir después del tiempo especificado
    setTimeout(function() {
        window.location.href = url;
    }, tiempo);
}