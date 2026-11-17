<?php
// Clase base de los modelos: entrega la conexión PDO

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }
}
