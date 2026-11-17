<?php
// Modelo de la tabla preguntas: banco de preguntas de la evaluación y CRUD del administrador

class Pregunta extends Model
{
    private const SELECT_BASE = 'SELECT p.*, n.nombre AS nivel, t.nombre AS tipo
                                 FROM preguntas p
                                 INNER JOIN niveles n ON n.id_nivel = p.id_nivel
                                 INNER JOIN tipos_compuesto t ON t.id_tipo = p.id_tipo';

    /** Listado para el administrador (filtros por nivel, tipo y texto). Incluye desactivadas. */
    public function listar(int $idNivel = 0, int $idTipo = 0, string $texto = ''): array
    {
        $sql = self::SELECT_BASE . ' WHERE 1 = 1';
        $params = [];
        if ($idNivel > 0) {
            $sql .= ' AND p.id_nivel = :nivel';
            $params[':nivel'] = $idNivel;
        }
        if ($idTipo > 0) {
            $sql .= ' AND p.id_tipo = :tipo';
            $params[':tipo'] = $idTipo;
        }
        if ($texto !== '') {
            $sql .= ' AND p.enunciado LIKE :texto';
            $params[':texto'] = '%' . $texto . '%';
        }
        $sql .= ' ORDER BY p.id_nivel, p.id_pregunta';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare(self::SELECT_BASE . ' WHERE p.id_pregunta = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** Preguntas activas de un nivel, en orden aleatorio. */
    public function obtenerAleatorias(int $idNivel, int $cantidad): array
    {
        $st = $this->db->prepare('SELECT * FROM preguntas WHERE id_nivel = :nivel AND estado = 1 ORDER BY RAND() LIMIT :cantidad');
        $st->bindValue(':nivel', $idNivel, PDO::PARAM_INT);
        $st->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    /** Devuelve las preguntas con esos ids, indexadas por id_pregunta. */
    public function obtenerPorIds(array $ids): array
    {
        if (count($ids) === 0) {
            return [];
        }
        $marcas = implode(',', array_fill(0, count($ids), '?'));
        $st = $this->db->prepare('SELECT * FROM preguntas WHERE id_pregunta IN (' . $marcas . ')');
        $st->execute(array_values($ids));
        $resultado = [];
        foreach ($st->fetchAll() as $fila) {
            $resultado[(int) $fila['id_pregunta']] = $fila;
        }
        return $resultado;
    }

    public function crear(array $d): int
    {
        $sql = 'INSERT INTO preguntas (id_nivel, id_tipo, enunciado, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta)
                VALUES (:nivel, :tipo, :enunciado, :a, :b, :c, :d, :correcta)';
        $st = $this->db->prepare($sql);
        $st->execute($this->parametros($d));
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $d): bool
    {
        $sql = 'UPDATE preguntas SET id_nivel = :nivel, id_tipo = :tipo, enunciado = :enunciado,
                opcion_a = :a, opcion_b = :b, opcion_c = :c, opcion_d = :d, respuesta_correcta = :correcta
                WHERE id_pregunta = :id';
        $st = $this->db->prepare($sql);
        $params = $this->parametros($d);
        $params[':id'] = $id;
        return $st->execute($params);
    }

    /** Desactivación lógica: 0 = desactivar, 1 = activar. */
    public function cambiarEstado(int $id, int $estado): bool
    {
        $st = $this->db->prepare('UPDATE preguntas SET estado = :estado WHERE id_pregunta = :id');
        return $st->execute([':estado' => $estado, ':id' => $id]);
    }

    public function contar(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM preguntas WHERE estado = 1')->fetchColumn();
    }

    private function parametros(array $d): array
    {
        return [
            ':nivel'     => (int) $d['id_nivel'],
            ':tipo'      => (int) $d['id_tipo'],
            ':enunciado' => $d['enunciado'],
            ':a'         => $d['opcion_a'],
            ':b'         => $d['opcion_b'],
            ':c'         => $d['opcion_c'],
            ':d'         => $d['opcion_d'],
            ':correcta'  => $d['respuesta_correcta'],
        ];
    }
}
