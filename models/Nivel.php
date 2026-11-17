<?php
// Modelo de la tabla niveles

class Nivel extends Model
{
    public function listar(): array
    {
        return $this->db->query('SELECT id_nivel, nombre, descripcion, cantidad_preguntas FROM niveles ORDER BY id_nivel')->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $st = $this->db->prepare('SELECT id_nivel, nombre, descripcion, cantidad_preguntas FROM niveles WHERE id_nivel = :id');
        $st->execute([':id' => $id]);
        $fila = $st->fetch();
        return $fila ?: null;
    }
}
