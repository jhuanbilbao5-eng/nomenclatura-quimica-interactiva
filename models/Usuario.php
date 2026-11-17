<?php
// Modelo de la tabla usuarios

class Usuario extends Model
{
    /** Registra un estudiante con el rol "Usuario". Devuelve el id creado. */
    public function registrar(string $nombres, string $apellidos, string $correo, string $clave): int
    {
        $sql = "INSERT INTO usuarios (nombres, apellidos, correo, contrasena, id_rol)
                VALUES (:nombres, :apellidos, :correo, :clave,
                        (SELECT id_rol FROM roles WHERE nombre = 'Usuario'))";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':nombres'   => $nombres,
            ':apellidos' => $apellidos,
            ':correo'    => $correo,
            ':clave'     => password_hash($clave, PASSWORD_DEFAULT),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function existeCorreo(string $correo): bool
    {
        $st = $this->db->prepare('SELECT COUNT(*) FROM usuarios WHERE correo = :correo');
        $st->execute([':correo' => $correo]);
        return (int) $st->fetchColumn() > 0;
    }

    /** Devuelve el usuario (con el nombre de su rol) si el correo y la contraseña son correctos y la cuenta está activa. */
    public function autenticar(string $correo, string $clave): ?array
    {
        $sql = 'SELECT u.id_usuario, u.nombres, u.apellidos, u.contrasena, r.nombre AS rol
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.id_rol
                WHERE u.correo = :correo AND u.estado = 1
                LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute([':correo' => $correo]);
        $usuario = $st->fetch();
        if ($usuario && password_verify($clave, $usuario['contrasena'])) {
            return $usuario;
        }
        return null;
    }

    /** Datos de bloqueo de una cuenta (para limitar los intentos de inicio de sesión). */
    public function estadoBloqueo(string $correo): ?array
    {
        $st = $this->db->prepare('SELECT id_usuario, intentos_fallidos, bloqueado_hasta FROM usuarios WHERE correo = :correo');
        $st->execute([':correo' => $correo]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** Suma un intento fallido; al llegar al máximo, bloquea la cuenta unos minutos. */
    public function registrarFallo(int $id, int $intentosActuales): void
    {
        $intentos = $intentosActuales + 1;
        $bloqueo = null;
        if ($intentos >= LOGIN_MAX_INTENTOS) {
            $bloqueo = date('Y-m-d H:i:s', time() + LOGIN_BLOQUEO_MINUTOS * 60);
            $intentos = 0;
        }
        $st = $this->db->prepare('UPDATE usuarios SET intentos_fallidos = :intentos, bloqueado_hasta = :bloqueo WHERE id_usuario = :id');
        $st->execute([':intentos' => $intentos, ':bloqueo' => $bloqueo, ':id' => $id]);
    }

    /** Inicio de sesión correcto: se reinician los intentos fallidos. */
    public function reiniciarIntentos(int $id): void
    {
        $st = $this->db->prepare('UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id_usuario = :id');
        $st->execute([':id' => $id]);
    }

    /** Comprueba la contraseña actual de un usuario (para cambiarla). */
    public function verificarClave(int $id, string $clave): bool
    {
        $st = $this->db->prepare('SELECT contrasena FROM usuarios WHERE id_usuario = :id');
        $st->execute([':id' => $id]);
        $hash = $st->fetchColumn();
        return $hash !== false && password_verify($clave, $hash);
    }

    public function cambiarClave(int $id, string $nuevaClave): void
    {
        $st = $this->db->prepare('UPDATE usuarios SET contrasena = :clave WHERE id_usuario = :id');
        $st->execute([':clave' => password_hash($nuevaClave, PASSWORD_DEFAULT), ':id' => $id]);
    }

    /** Cantidad de estudiantes (rol Usuario) activos, para el panel de administración. */
    public function contarEstudiantes(): int
    {
        $sql = "SELECT COUNT(*) FROM usuarios u INNER JOIN roles r ON r.id_rol = u.id_rol
                WHERE r.nombre = 'Usuario' AND u.estado = 1";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare('SELECT id_usuario, nombres, apellidos, correo, estado FROM usuarios WHERE id_usuario = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }
}
