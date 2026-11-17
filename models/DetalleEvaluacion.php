<?php
// Modelo de la tabla detalle_evaluacion (respuestas de cada evaluación)

class DetalleEvaluacion extends Model
{
    public function guardar(int $idEvaluacion, int $idPregunta, ?string $respuesta, int $esCorrecta): void
    {
        $sql = 'INSERT INTO detalle_evaluacion (id_evaluacion, id_pregunta, respuesta_usuario, es_correcta)
                VALUES (:evaluacion, :pregunta, :respuesta, :correcta)';
        $st = $this->db->prepare($sql);
        $st->execute([
            ':evaluacion' => $idEvaluacion,
            ':pregunta'   => $idPregunta,
            ':respuesta'  => $respuesta,
            ':correcta'   => $esCorrecta,
        ]);
    }

    /** Respuestas de una evaluación junto con el texto de cada pregunta (para la revisión). */
    public function listarPorEvaluacion(int $idEvaluacion): array
    {
        $sql = 'SELECT d.*, p.enunciado, p.opcion_a, p.opcion_b, p.opcion_c, p.opcion_d, p.respuesta_correcta
                FROM detalle_evaluacion d
                INNER JOIN preguntas p ON p.id_pregunta = d.id_pregunta
                WHERE d.id_evaluacion = :evaluacion
                ORDER BY d.id_detalle';
        $st = $this->db->prepare($sql);
        $st->execute([':evaluacion' => $idEvaluacion]);
        return $st->fetchAll();
    }
}
