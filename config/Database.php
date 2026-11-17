<?php
// Conexión a MySQL con PDO (una sola conexión para toda la petición)

class Database
{
    private static ?PDO $conexion = null;

    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            try {
                self::$conexion = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                die('No se pudo conectar a la base de datos. Verifique config/config.php y que MySQL esté iniciado.');
            }
        }
        return self::$conexion;
    }
}
