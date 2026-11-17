<?php
// CRUD de preguntas de evaluación (RF11) - solo Administrador

class PreguntaController extends Controller
{
    private Pregunta $modelo;

    public function __construct()
    {
        Auth::requerirRol('Administrador');
        $this->modelo = new Pregunta();
    }

    /** P15: listado con filtros por nivel, tipo y texto. */
    public function index(): void
    {
        $idNivel = (int) $this->get('nivel', '0');
        $idTipo = (int) $this->get('tipo', '0');
        $texto = $this->get('q');

        $this->vista('admin/preguntas/index', [
            'titulo'    => 'Preguntas de evaluación',
            'idNivel'   => $idNivel,
            'idTipo'    => $idTipo,
            'texto'     => $texto,
            'niveles'   => (new Nivel())->listar(),
            'tipos'     => (new TipoCompuesto())->listar(),
            'preguntas' => $this->modelo->listar($idNivel, $idTipo, $texto),
        ]);
    }

    /** Consultar: muestra una pregunta con sus opciones y la respuesta correcta. */
    public function ver(): void
    {
        $pregunta = $this->modelo->obtener((int) $this->get('id', '0'));
        if ($pregunta === null) {
            $this->flash('danger', 'La pregunta no existe.');
            redirigir('pregunta', 'index');
        }

        $this->vista('admin/preguntas/ver', ['titulo' => 'Consultar pregunta', 'pregunta' => $pregunta]);
    }

    /** P16: formulario para registrar una pregunta. */
    public function crear(): void
    {
        $errores = [];
        $datos = [
            'id_nivel' => 0, 'id_tipo' => 0, 'enunciado' => '', 'opcion_a' => '', 'opcion_b' => '',
            'opcion_c' => '', 'opcion_d' => '', 'respuesta_correcta' => '',
        ];

        if ($this->esPost()) {
            $datos = $this->datosFormulario();
            $errores = $this->validar($datos);
            if (count($errores) === 0) {
                $this->modelo->crear($datos);
                $this->flash('success', 'Pregunta registrada correctamente.');
                redirigir('pregunta', 'index');
            }
        }

        $this->mostrarFormulario('Nueva pregunta', 'crear', $datos, $errores, 0);
    }

    /** P16: formulario para modificar una pregunta. */
    public function editar(): void
    {
        $id = (int) $this->get('id', '0');
        $actual = $this->modelo->obtener($id);
        if ($actual === null) {
            $this->flash('danger', 'La pregunta no existe.');
            redirigir('pregunta', 'index');
        }

        $errores = [];
        $datos = $actual;

        if ($this->esPost()) {
            $datos = $this->datosFormulario();
            $errores = $this->validar($datos);
            if (count($errores) === 0) {
                $this->modelo->actualizar($id, $datos);
                $this->flash('success', 'Pregunta actualizada correctamente.');
                redirigir('pregunta', 'index');
            }
        }

        $this->mostrarFormulario('Modificar pregunta', 'editar', $datos, $errores, $id);
    }

    public function desactivar(): void
    {
        $this->cambiarEstado(0, 'Pregunta desactivada.');
    }

    public function activar(): void
    {
        $this->cambiarEstado(1, 'Pregunta activada.');
    }

    // ---- Auxiliares ----

    private function cambiarEstado(int $estado, string $mensaje): void
    {
        if ($this->esPost()) {
            $id = (int) $this->post('id', '0');
            if ($this->modelo->obtener($id) !== null) {
                $this->modelo->cambiarEstado($id, $estado);
                $this->flash('success', $mensaje);
            }
        }
        redirigir('pregunta', 'index');
    }

    private function mostrarFormulario(string $titulo, string $accion, array $datos, array $errores, int $id): void
    {
        $this->vista('admin/preguntas/form', [
            'titulo'  => $titulo,
            'accion'  => $accion,
            'datos'   => $datos,
            'errores' => $errores,
            'id'      => $id,
            'niveles' => (new Nivel())->listar(),
            'tipos'   => (new TipoCompuesto())->listar(),
        ]);
    }

    private function datosFormulario(): array
    {
        return [
            'id_nivel'           => (int) $this->post('id_nivel', '0'),
            'id_tipo'            => (int) $this->post('id_tipo', '0'),
            'enunciado'          => $this->post('enunciado'),
            'opcion_a'           => $this->post('opcion_a'),
            'opcion_b'           => $this->post('opcion_b'),
            'opcion_c'           => $this->post('opcion_c'),
            'opcion_d'           => $this->post('opcion_d'),
            'respuesta_correcta' => strtoupper($this->post('respuesta_correcta')),
        ];
    }

    private function validar(array $d): array
    {
        $errores = [];

        if ($d['id_nivel'] <= 0 || (new Nivel())->obtener($d['id_nivel']) === null) {
            $errores[] = 'Seleccione un nivel válido.';
        }
        if ($d['id_tipo'] <= 0 || (new TipoCompuesto())->obtener($d['id_tipo']) === null) {
            $errores[] = 'Seleccione un tipo de compuesto válido.';
        }
        if (!Validator::requerido($d['enunciado']) || !Validator::longitudMaxima($d['enunciado'], 255)) {
            $errores[] = 'El enunciado es obligatorio (máximo 255 caracteres).';
        }

        $opciones = [];
        foreach (['opcion_a', 'opcion_b', 'opcion_c', 'opcion_d'] as $campo) {
            if (!Validator::requerido($d[$campo]) || !Validator::longitudMaxima($d[$campo], 100)) {
                $errores[] = 'Las cuatro opciones son obligatorias (máximo 100 caracteres cada una).';
                break;
            }
            $opciones[] = mb_strtolower($d[$campo]);
        }
        if (count($opciones) === 4 && count(array_unique($opciones)) < 4) {
            $errores[] = 'Las cuatro opciones deben ser distintas.';
        }

        if (!in_array($d['respuesta_correcta'], ['A', 'B', 'C', 'D'], true)) {
            $errores[] = 'Elija la respuesta correcta (A, B, C o D).';
        }

        return $errores;
    }
}
