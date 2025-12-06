# ATM - Cajero Simulado

Proyecto simple de cajero automático desarrollado en PHP/JS para demostraciones y pruebas locales.

## Resumen

Aplicación que simula operaciones básicas de un cajero (login por tarjeta+PIN, retiros, depósitos, consulta de saldo y generación de comprobantes en PDF). Los datos de usuarios, saldos e historial se almacenan en la sesión PHP para facilitar pruebas locales sin base de datos.

## Funcionalidades principales

- Inicio de sesión por número de tarjeta y PIN (simulado).
- Interfaz de depósito con validación (monto > 0 y múltiplo de 10).
- Interfaz de retiro con opciones rápidas y cálculo de denominaciones/billetes.
- Lógica de transacciones centralizada en `control/contenedorTransac.php` (métodos: `retiro`, `deposito`, `consultarSaldo`, `obtenerHistorial`).
- Modal/overlay de procesamiento en JS (`proceso.js`) para feedback visual antes de mostrar comprobantes.
- Internacionalización sencilla: archivos en `idioma/` (`es.php`, `en.php`, `pt.php`).
- Generación de PDFs usando la librería `fpdf186/` (vistas relacionadas como `pdfRetiro.php`, `pdfHistorial.php`).

## Demo - Credenciales de ejemplo

Usa estas cuentas para probar la app en local:

```
Tarjeta: 1234567890  PIN: 1234  (Juan Perez)
Tarjeta: 0987654321  PIN: 4321  (Maria Lopez)
```

## Estructura relevante del proyecto

- `index.php` — inicializa datos de ejemplo y redirige a `vista/login.php`.
- `vista/` — vistas principales: `login.php`, `menu.php`, `retirar.php`, `deposito.php`, `detalleRetiro.php`, `detalleDeposito.php`, etc.
- `control/contenedorTransac.php` — controlador de transacciones y lógica de negocios.
- `proceso.js` — modal/overlay para procesos (retiro, depósito, consulta).
- `idioma/` — archivos de traducción por idioma.
- `fpdf186/` — librería FPDF para generar comprobantes PDF.

## Instalación y uso (local con XAMPP)

1. Copia la carpeta del proyecto dentro de la carpeta `htdocs` de XAMPP (p. ej. `C:\xampp\htdocs\ATM`).
2. Asegúrate de que Apache y PHP estén activos en XAMPP.
3. Abre en el navegador: `http://localhost/ATM/atm-serfassil/index.php` (o ajusta según tu ruta).
4. Inicia sesión con una de las credenciales demo arriba.

## Consideraciones técnicas y de seguridad

- Persistencia: actualmente los usuarios y transacciones se guardan en `$_SESSION`. Para un entorno real, migrar a una base de datos (MySQL, PostgreSQL, SQLite).
- PINs en texto claro: almacenar PINs usando hashing seguro (bcrypt) antes de pasar a producción.
- Tokens de sesión: implementar manejo seguro, expiración, regeneración y protección HTTPS.
- CSRF/XSS: añadir tokens CSRF en formularios y sanitizar entradas del usuario.
- Concurrencia: al pasar a BD, usar transacciones para evitar condiciones de carrera en retiros simultáneos.

## Mejoras sugeridas (prioridad alta→baja)

- Persistencia en BD y migración de la lógica de sesión a consultas seguras.
- Hash de PINs y mejora del manejo de sesiones (expiración, logout efectivo).
- Validaciones adicionales en servidor (límites de retiro, protección contra montos negativos o manipulados).
- Tests unitarios para `TransactionController` (cálculo de billetes, validaciones).
- Manejo de logs y auditoría de operaciones.

## Archivos para revisar primero

- `control/contenedorTransac.php` — ver reglas de negocio.
- `vista/retirar.php` y `vista/deposito.php` — ver validaciones y flujo UX.
- `proceso.js` — mejorar/simplificar modal si es necesario.

## ¿Qué puedo hacer ahora?

- Ayudarte a migrar la persistencia a una base de datos (creo tablas y adapto el controlador).
- Implementar hashing de PINs y autenticación más segura.
- Añadir protección CSRF y validaciones adicionales.

Si quieres, inicio la tarea que elijas y la implemento.

---
_Generado el 6 de diciembre de 2025._
