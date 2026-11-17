<?php
// Módulo Evaluación (RF07, RF08, RF13): elegir nivel, resolver, calificar y guardar el resultado

class EvaluacionController extends Controller
{
    public function __construct()
    {
        // Solo los estudiantes (rol Usuario) rinden evaluaciones
        Auth::requerirRol('Usuario');
    }

    /** P08: elegir el nivel. */
    public function index(): void
    {
        $this->vista('evaluacion/niveles', ['titulo' => 'Evaluación', 'niveles' => (new Nivel())->listar()]);
    }

    /** P09: genera las preguntas del nivel elegido y las muestra. */
    public function iniciar(): void
    {
        $nivel = (new Nivel())->obtener((int) $this->get('nivel', '0'));
        if ($nivel === null) {
            $this->flash('danger', 'Seleccione un nivel válido.');
            redirigir('evaluacion', 'index');
        }

        $preguntas = (new Pregunta())->obtenerAleatorias((int) $nivel['id_nivel'], (int) $nivel['cantidad_preguntas']);
        if (count($preguntas) === 0) {
            $this->flash('warning', 'Todavía no hay preguntas registradas para ese nivel.');
            redirigir('evaluacion', 'index');
        }

        // Se recuerdan las preguntas presentadas para calificar después
        $_SESSION['examen'] = [
            'id_nivel' => (int) $nivel['id_nivel'],
            'ids'      => array_map(fn($p) => (int) $p['id_pregunta'], $preguntas),
        ];

        $this->vista('evaluacion/examen', ['titulo' => 'Evaluación: ' . $nivel['nombre'], 'nivel' => $nivel, 'preguntas' => $preguntas]);
    }

    /** Califica las respuestas, guarda la evaluación y muestra el resultado. */
    public function calificar(): void
    {
        if (!$this->esPost() || !isset($_SESSION['examen'])) {
            redirigir('evaluacion', 'index');
        }

        $examen = $_SESSION['examen'];
        $enviadas = isset($_POST['respuesta']) && is_array($_POST['respuesta']) ? $_POST['respuesta'] : [];
        $preguntas = (new Pregunta())->obtenerPorIds($examen['ids']);

        $total = count($examen['ids']);
        $correctas = 0;
        $detalle = [];

        foreach ($examen['ids'] as $idPregunta) {
            $respuesta = isset($enviadas[$idPregunta]) ? strtoupper(trim((string) $enviadas[$idPregunta])) : '';
            if (!in_array($respuesta, ['A', 'B', 'C', 'D'], true)) {
                $respuesta = null; // sin responder o valor no permitido
            }
            $esCorrecta = ($respuesta !== null && isset($preguntas[$idPregunta])
                && $respuesta === $preguntas[$idPregunta]['respuesta_correcta']) ? 1 : 0;
            $correctas += $esCorrecta;
            $detalle[] = ['id' => $idPregunta, 'respuesta' => $respuesta, 'correcta' => $esCorrecta];
        }

        $nota = $total > 0 ? round($correctas / $total * 100, 2) : 0;

        $db = Database::conectar();
        try {
            $db->beginTransaction();
            $idEvaluacion = (new Evaluacion())->crear(Auth::id(), $examen['id_nivel'], $total, $correctas, $nota);
            $modeloDetalle = new DetalleEvaluacion();
            foreach ($detalle as $d) {
                $modeloDetalle->guardar($idEvaluacion, $d['id'], $d['respuesta'], $d['correcta']);
            }
            $db->commit();
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->flash('danger', 'No se pudo guardar la evaluación. Intente nuevamente.');
            redirigir('evaluacion', 'index');
        }

        unset($_SESSION['examen']);
        redirigir('evaluacion', 'resultado', ['id' => $idEvaluacion]);
    }

    /** P10: resultado de una evaluación (solo el dueño la puede ver). */
    public function resultado(): void
    {
        $evaluacion = (new Evaluacion())->obtener((int) $this->get('id', '0'));
        if ($evaluacion === null || (int) $evaluacion['id_usuario'] !== Auth::id()) {
            $this->flash('danger', 'No se encontró el resultado solicitado.');
            redirigir('evaluacion', 'index');
        }

        $this->vista('evaluacion/resultado', [
            'titulo'     => 'Resultado',
            'evaluacion' => $evaluacion,
            'detalle'    => (new DetalleEvaluacion())->listarPorEvaluacion((int) $evaluacion['id_evaluacion']),
        ]);
    }
}
