<?php
// Modelo de la tabla roles

class Rol extends Model
{
    public function listar(): array
    {
        return $this->db->query('SELECT id_rol, nombre FROM roles ORDER BY id_rol')->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare('SELECT id_rol, nombre FROM roles WHERE id_rol = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }
}
