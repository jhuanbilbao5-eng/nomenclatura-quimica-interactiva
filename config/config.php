<?php
// Configuración general del proyecto Nomenclatura Química Interactiva

define('BASE_PATH', dirname(__DIR__));

// La URL base se detecta sola (XAMPP: http://localhost/nomenclatura_quimica/public/ · hosting: https://dominio/public/)
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/');

define('APP_NOMBRE', 'Nomenclatura Química Interactiva');

// Zona horaria de Bolivia (fechas de las evaluaciones y del reporte PDF)
date_default_timezone_set('America/La_Paz');

// ---------------------------------------------------------------------
// Datos de conexión a MySQL
// En el HOSTING (InfinityFree u otro) se crea el archivo config/credenciales.php
// copiando config/credenciales.ejemplo.php y escribiendo los datos que da el hosting.
// Ese archivo NO se sube a GitHub (está en .gitignore) para no publicar la contraseña.
// Si no existe, se usan los valores por defecto de XAMPP.
// ---------------------------------------------------------------------
if (is_file(__DIR__ . '/credenciales.php')) {
    require __DIR__ . '/credenciales.php';
}
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'nomenclatura_quimica');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Modo depuración: muestra los errores de PHP solo cuando se trabaja en la computadora (localhost).
// En el sitio publicado los errores no se muestran al visitante (se guardan en el registro del servidor).
$hostActual = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
defined('APP_DEBUG') || define('APP_DEBUG', in_array($hostActual, ['localhost', '127.0.0.1'], true));

// Seguridad
define('SESION_MINUTOS', 30);          // la sesión se cierra tras 30 minutos sin actividad
define('LOGIN_MAX_INTENTOS', 5);       // intentos fallidos antes de bloquear la cuenta
define('LOGIN_BLOQUEO_MINUTOS', 5);    // minutos de bloqueo

// Carpeta de librerías (FPDF para los reportes)
define('LIBS_PATH', BASE_PATH . '/libs');
