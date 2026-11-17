<?php
// Manejo de sesión y control de acceso por rol

class Auth
{
    public static function iniciar(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id'     => (int) $usuario['id_usuario'],
            'nombre' => $usuario['nombres'] . ' ' . $usuario['apellidos'],
            'rol'    => $usuario['rol'],
        ];
    }

    /** Cierra la sesión: borra todos los datos y genera un identificador nuevo (el anterior se elimina). */
    public static function cerrar(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    /** Cierra la sesión automáticamente si el usuario estuvo inactivo más de SESION_MINUTOS. */
    public static function verificarInactividad(): void
    {
        if (!self::estaLogueado()) {
            return;
        }
        $ultimo = $_SESSION['ultimo_acceso'] ?? time();
        if (time() - $ultimo > SESION_MINUTOS * 60) {
            self::cerrar();
            $_SESSION['flash'] = ['tipo' => 'warning', 'mensaje' => 'Su sesión se cerró por inactividad. Ingrese nuevamente.'];
            redirigir('auth', 'login');
        }
        $_SESSION['ultimo_acceso'] = time();
    }

    public static function usuario(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    public static function id(): ?int
    {
        return isset($_SESSION['usuario']) ? (int) $_SESSION['usuario']['id'] : null;
    }

    public static function estaLogueado(): bool
    {
        return isset($_SESSION['usuario']);
    }

    public static function esAdmin(): bool
    {
        return (self::usuario()['rol'] ?? '') === 'Administrador';
    }

    /** Si no hay sesión, envía al inicio de sesión. */
    public static function requerirLogin(): void
    {
        if (!self::estaLogueado()) {
            redirigir('auth', 'login');
        }
    }

    /** Si el rol no coincide, redirige a la pantalla de inicio del usuario. */
    public static function requerirRol(string $rol): void
    {
        self::requerirLogin();
        if (self::usuario()['rol'] !== $rol) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensaje' => 'No tiene permiso para entrar a esa pantalla.'];
            if (self::esAdmin()) {
                redirigir('home', 'panel');
            }
            redirigir('home', 'index');
        }
    }
}
