<?php
// Funciones auxiliares usadas en controladores y vistas

/** Escapa texto para mostrarlo en HTML (evita scripts maliciosos). */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Construye una URL del sistema: url('compuesto', 'editar', ['id' => 5]) */
function url(string $c, string $a = 'index', array $params = []): string
{
    $query = array_merge(['c' => $c, 'a' => $a], $params);
    return BASE_URL . 'index.php?' . http_build_query($query);
}

/** Redirige a otra acción y termina la petición. */
function redirigir(string $c, string $a = 'index', array $params = []): void
{
    header('Location: ' . url($c, $a, $params));
    exit;
}

/** Token CSRF de la sesión: protege los formularios contra envíos falsificados desde otros sitios. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Campo oculto con el token CSRF; se coloca dentro de cada formulario POST. */
function campo_csrf(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Muestra una fórmula con los números como subíndices: Fe2O3 -> Fe<sub>2</sub>O<sub>3</sub> */
function formula_html(string $formula): string
{
    return preg_replace('/(\d+)/', '<sub>$1</sub>', e($formula));
}
