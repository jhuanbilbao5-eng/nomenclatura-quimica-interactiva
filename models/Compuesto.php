<?php
// Modelo de la tabla compuestos: catálogo para Formular, Nombrar, repositorio y CRUD del administrador

class Compuesto extends Model
{
    private const SELECT_BASE = 'SELECT c.*, t.nombre AS tipo
                                 FROM compuestos c
                                 INNER JOIN tipos_compuesto t ON t.id_tipo = c.id_tipo';

    /** Listado para el administrador (con búsqueda y filtro por tipo). Incluye desactivados. */
    public function listar(string $texto = '', int $idTipo = 0): array
    {
        $sql = self::SELECT_BASE . ' WHERE 1 = 1';
        $params = [];
        if ($texto !== '') {
            $sql .= ' AND (c.formula LIKE :t1 OR c.nombre_tradicional LIKE :t2 OR c.nombre_stock LIKE :t3
                           OR c.nombre_sistematico LIKE :t4 OR c.nombre_comun LIKE :t5)';
            for ($i = 1; $i <= 5; $i++) {
                $params[':t' . $i] = '%' . $texto . '%';
            }
        }
        if ($idTipo > 0) {
            $sql .= ' AND c.id_tipo = :tipo';
            $params[':tipo'] = $idTipo;
        }
        $sql .= ' ORDER BY c.formula';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    /** Repositorio de la pantalla principal: solo compuestos activos marcados como cotidianos. */
    public function listarCotidianos(string $texto = '', int $idTipo = 0): array
    {
        $sql = self::SELECT_BASE . ' WHERE c.cotidiano = 1 AND c.estado = 1';
        $params = [];
        if ($texto !== '') {
            $sql .= ' AND (c.nombre_comun LIKE :t1 OR c.formula LIKE :t2 OR c.nombre_tradicional LIKE :t3
                           OR c.nombre_stock LIKE :t4 OR c.nombre_sistematico LIKE :t5)';
            for ($i = 1; $i <= 5; $i++) {
                $params[':t' . $i] = '%' . $texto . '%';
            }
        }
        if ($idTipo > 0) {
            $sql .= ' AND c.id_tipo = :tipo';
            $params[':tipo'] = $idTipo;
        }
        $sql .= ' ORDER BY c.nombre_comun';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare(self::SELECT_BASE . ' WHERE c.id_compuesto = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** Formular: busca por fórmula exacta (la columna distingue mayúsculas y minúsculas). */
    public function buscarPorFormula(string $formula): ?array
    {
        $st = $this->db->prepare(self::SELECT_BASE . ' WHERE c.formula = :formula AND c.estado = 1 LIMIT 1');
        $st->execute([':formula' => $formula]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** Nombrar: busca en los tres nombres y en el nombre común (sin distinguir mayúsculas ni tildes). */
    public function buscarPorNombre(string $nombre): ?array
    {
        $sql = self::SELECT_BASE . ' WHERE c.estado = 1 AND (c.nombre_tradicional = :n1 OR c.nombre_stock = :n2
                OR c.nombre_sistematico = :n3 OR c.nombre_comun = :n4) LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute([':n1' => $nombre, ':n2' => $nombre, ':n3' => $nombre, ':n4' => $nombre]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** Indica si ya existe otro compuesto con esa fórmula (para mantenerla única). */
    public function existeFormula(string $formula, int $excluirId = 0): bool
    {
        $st = $this->db->prepare('SELECT COUNT(*) FROM compuestos WHERE formula = :formula AND id_compuesto <> :id');
        $st->execute([':formula' => $formula, ':id' => $excluirId]);
        return (int) $st->fetchColumn() > 0;
    }

    public function crear(array $d): int
    {
        $sql = 'INSERT INTO compuestos (formula, nombre_tradicional, nombre_stock, nombre_sistematico,
                                        nombre_comun, uso, cotidiano, id_tipo)
                VALUES (:formula, :tradicional, :stock, :sistematico, :comun, :uso, :cotidiano, :tipo)';
        $st = $this->db->prepare($sql);
        $st->execute($this->parametros($d));
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $d): bool
    {
        $sql = 'UPDATE compuestos SET formula = :formula, nombre_tradicional = :tradicional,
                nombre_stock = :stock, nombre_sistematico = :sistematico, nombre_comun = :comun,
                uso = :uso, cotidiano = :cotidiano, id_tipo = :tipo
                WHERE id_compuesto = :id';
        $st = $this->db->prepare($sql);
        $params = $this->parametros($d);
        $params[':id'] = $id;
        return $st->execute($params);
    }

    /** Desactivación lógica: 0 = desactivar, 1 = activar. */
    public function cambiarEstado(int $id, int $estado): bool
    {
        $st = $this->db->prepare('UPDATE compuestos SET estado = :estado WHERE id_compuesto = :id');
        return $st->execute([':estado' => $estado, ':id' => $id]);
    }

    public function contar(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM compuestos WHERE estado = 1')->fetchColumn();
    }

    private function parametros(array $d): array
    {
        return [
            ':formula'     => $d['formula'],
            ':tradicional' => $d['nombre_tradicional'],
            ':stock'       => $d['nombre_stock'],
            ':sistematico' => $d['nombre_sistematico'],
            ':comun'       => $d['nombre_comun'] !== '' ? $d['nombre_comun'] : null,
            ':uso'         => $d['uso'] !== '' ? $d['uso'] : null,
            ':cotidiano'   => (int) $d['cotidiano'],
            ':tipo'        => (int) $d['id_tipo'],
        ];
    }
}
