<?php
// Modelo de la tabla tipos_compuesto

class TipoCompuesto extends Model
{
    public function listar(): array
    {
        return $this->db->query('SELECT id_tipo, nombre, descripcion FROM tipos_compuesto ORDER BY id_tipo')->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare('SELECT id_tipo, nombre, descripcion FROM tipos_compuesto WHERE id_tipo = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }
}
