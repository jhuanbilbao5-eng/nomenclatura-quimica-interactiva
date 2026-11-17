<?php
// Clase base de los controladores

abstract class Controller
{
    /** Carga una vista dentro de la plantilla (cabecera + vista + pie). */
    protected function vista(string $ruta, array $parametros = []): void
    {
        // Cada clave del arreglo se convierte en una variable de la vista ($titulo, $datos, $errores...)
        extract($parametros, EXTR_SKIP);
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/' . $ruta . '.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    /** Guarda un mensaje que se mostrará una sola vez en la siguiente pantalla. */
    protected function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }

    protected function esPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /** Lee un campo enviado por POST (sin espacios en los extremos). */
    protected function post(string $campo, string $defecto = ''): string
    {
        return isset($_POST[$campo]) ? trim((string) $_POST[$campo]) : $defecto;
    }

    /** Lee un parámetro de la URL (GET). */
    protected function get(string $campo, string $defecto = ''): string
    {
        return isset($_GET[$campo]) ? trim((string) $_GET[$campo]) : $defecto;
    }
}
