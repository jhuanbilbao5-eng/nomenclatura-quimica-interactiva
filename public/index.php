<?php
// Controlador frontal: todas las peticiones entran por este archivo.

require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/core/helpers.php';

// Errores: visibles solo en la computadora de desarrollo (APP_DEBUG)
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

// Carga automática de clases (core, config, controllers y models)
spl_autoload_register(function (string $clase) {
    foreach (['core', 'config', 'controllers', 'models'] as $carpeta) {
        $archivo = BASE_PATH . '/' . $carpeta . '/' . $clase . '.php';
        if (is_file($archivo)) {
            require_once $archivo;
            return;
        }
    }
});

// Si ocurre un error inesperado, se muestra un mensaje amable en lugar del detalle técnico
set_exception_handler(function (Throwable $e) {
    error_log('[Nomenclatura] ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (APP_DEBUG) {
        echo '<pre>' . e((string) $e) . '</pre>';
    } else {
        echo '<h1>Ocurrió un error</h1><p>Intente nuevamente en unos momentos.</p>';
    }
});

// Cabeceras de seguridad del navegador
header('X-Frame-Options: DENY');                 // evita que el sitio se cargue dentro de otra página
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

// Sesión con cookie protegida (no accesible desde JavaScript; solo por HTTPS si el sitio usa HTTPS)
$esHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => $esHttps]);
session_start();

// Cierra la sesión si estuvo inactiva más del tiempo permitido
Auth::verificarInactividad();

Router::despachar();
