<?php
// Modelo de la tabla evaluaciones

class Evaluacion extends Model
{
    public function crear(int $idUsuario, int $idNivel, int $total, int $correctas, float $nota): int
    {
        $sql = 'INSERT INTO evaluaciones (id_usuario, id_nivel, total_preguntas, correctas, nota)
                VALUES (:usuario, :nivel, :total, :correctas, :nota)';
        $st = $this->db->prepare($sql);
        $st->execute([
            ':usuario'   => $idUsuario,
            ':nivel'     => $idNivel,
            ':total'     => $total,
            ':correctas' => $correctas,
            ':nota'      => $nota,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function obtener(int $id): ?array
    {
        $sql = 'SELECT e.*, n.nombre AS nivel, CONCAT(u.nombres, \' \', u.apellidos) AS usuario
                FROM evaluaciones e
                INNER JOIN niveles n ON n.id_nivel = e.id_nivel
                INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
                WHERE e.id_evaluacion = :id';
        $st = $this->db->prepare($sql);
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /**
     * Historial con filtros. $idUsuario = 0 devuelve los de todos los usuarios (solo para el administrador).
     * Filtros: id_nivel (int), desde y hasta (AAAA-MM-DD).
     */
    public function listar(int $idUsuario = 0, int $idNivel = 0, string $desde = '', string $hasta = ''): array
    {
        $sql = 'SELECT e.*, n.nombre AS nivel, CONCAT(u.nombres, \' \', u.apellidos) AS usuario
                FROM evaluaciones e
                INNER JOIN niveles n ON n.id_nivel = e.id_nivel
                INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
                WHERE 1 = 1';
        $params = [];
        if ($idUsuario > 0) {
            $sql .= ' AND e.id_usuario = :usuario';
            $params[':usuario'] = $idUsuario;
        }
        if ($idNivel > 0) {
            $sql .= ' AND e.id_nivel = :nivel';
            $params[':nivel'] = $idNivel;
        }
        if ($desde !== '') {
            $sql .= ' AND DATE(e.fecha) >= :desde';
            $params[':desde'] = $desde;
        }
        if ($hasta !== '') {
            $sql .= ' AND DATE(e.fecha) <= :hasta';
            $params[':hasta'] = $hasta;
        }
        $sql .= ' ORDER BY e.fecha DESC';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function contar(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM evaluaciones')->fetchColumn();
    }
}
