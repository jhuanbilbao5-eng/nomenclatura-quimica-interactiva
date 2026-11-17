<?php
// Validaciones del lado del servidor (PHP)

class Validator
{
    public static function requerido(string $valor): bool
    {
        return trim($valor) !== '';
    }

    public static function correo(string $valor): bool
    {
        return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function soloLetras(string $valor): bool
    {
        return (bool) preg_match('/^[\p{L}\s]+$/u', $valor);
    }

    /** Fórmula química: letras, números y paréntesis. */
    public static function formula(string $valor): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9()]+$/', $valor);
    }

    /** Nombre químico: letras (con tildes), números, espacios, guion, paréntesis, coma y punto. */
    public static function nombreQuimico(string $valor): bool
    {
        return (bool) preg_match('/^[\p{L}0-9\s\-(),.]+$/u', $valor);
    }

    /** Contraseña segura: al menos 8 caracteres, con letras y números. */
    public static function claveSegura(string $valor): bool
    {
        return mb_strlen($valor) >= 8 && mb_strlen($valor) <= 72
            && preg_match('/\p{L}/u', $valor) && preg_match('/\d/', $valor);
    }

    public static function longitudMinima(string $valor, int $minimo): bool
    {
        return mb_strlen($valor) >= $minimo;
    }

    public static function longitudMaxima(string $valor, int $maximo): bool
    {
        return mb_strlen($valor) <= $maximo;
    }

    /** Fecha en formato AAAA-MM-DD. */
    public static function fecha(string $valor): bool
    {
        $f = DateTime::createFromFormat('Y-m-d', $valor);
        return $f !== false && $f->format('Y-m-d') === $valor;
    }
}
