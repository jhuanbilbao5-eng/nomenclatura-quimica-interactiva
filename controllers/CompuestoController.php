<?php
// CRUD de compuestos (RF10) - solo Administrador

class CompuestoController extends Controller
{
    private Compuesto $modelo;

    public function __construct()
    {
        Auth::requerirRol('Administrador');
        $this->modelo = new Compuesto();
    }

    /** P13: listado con búsqueda y filtro por tipo. */
    public function index(): void
    {
        $texto = $this->get('q');
        $idTipo = (int) $this->get('tipo', '0');

        $this->vista('admin/compuestos/index', [
            'titulo'      => 'Compuestos',
            'texto'       => $texto,
            'idTipo'      => $idTipo,
            'tipos'       => (new TipoCompuesto())->listar(),
            'compuestos'  => $this->modelo->listar($texto, $idTipo),
        ]);
    }

    /** Consultar: muestra todos los datos de un compuesto. */
    public function ver(): void
    {
        $compuesto = $this->modelo->obtener((int) $this->get('id', '0'));
        if ($compuesto === null) {
            $this->flash('danger', 'El compuesto no existe.');
            redirigir('compuesto', 'index');
        }

        $this->vista('admin/compuestos/ver', ['titulo' => 'Consultar compuesto', 'compuesto' => $compuesto]);
    }

    /** P14: formulario para registrar un compuesto. */
    public function crear(): void
    {
        $errores = [];
        $datos = $this->datosVacios();

        if ($this->esPost()) {
            $datos = $this->datosFormulario();
            $errores = $this->validar($datos, 0);
            if (count($errores) === 0) {
                $this->modelo->crear($datos);
                $this->flash('success', 'Compuesto registrado correctamente.');
                redirigir('compuesto', 'index');
            }
        }

        $this->mostrarFormulario('Nuevo compuesto', 'crear', $datos, $errores, 0);
    }

    /** P14: formulario para modificar un compuesto. */
    public function editar(): void
    {
        $id = (int) $this->get('id', '0');
        $actual = $this->modelo->obtener($id);
        if ($actual === null) {
            $this->flash('danger', 'El compuesto no existe.');
            redirigir('compuesto', 'index');
        }

        $errores = [];
        $datos = $actual;

        if ($this->esPost()) {
            $datos = $this->datosFormulario();
            $errores = $this->validar($datos, $id);
            if (count($errores) === 0) {
                $this->modelo->actualizar($id, $datos);
                $this->flash('success', 'Compuesto actualizado correctamente.');
                redirigir('compuesto', 'index');
            }
        }

        $this->mostrarFormulario('Modificar compuesto', 'editar', $datos, $errores, $id);
    }

    public function desactivar(): void
    {
        $this->cambiarEstado(0, 'Compuesto desactivado.');
    }

    public function activar(): void
    {
        $this->cambiarEstado(1, 'Compuesto activado.');
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
        redirigir('compuesto', 'index');
    }

    private function mostrarFormulario(string $titulo, string $accion, array $datos, array $errores, int $id): void
    {
        $this->vista('admin/compuestos/form', [
            'titulo'  => $titulo,
            'accion'  => $accion,
            'datos'   => $datos,
            'errores' => $errores,
            'id'      => $id,
            'tipos'   => (new TipoCompuesto())->listar(),
        ]);
    }

    private function datosVacios(): array
    {
        return [
            'formula' => '', 'nombre_tradicional' => '', 'nombre_stock' => '', 'nombre_sistematico' => '',
            'nombre_comun' => '', 'uso' => '', 'cotidiano' => 0, 'id_tipo' => 0,
        ];
    }

    private function datosFormulario(): array
    {
        return [
            'formula'            => preg_replace('/\s+/', '', $this->post('formula')),
            'nombre_tradicional' => $this->post('nombre_tradicional'),
            'nombre_stock'       => $this->post('nombre_stock'),
            'nombre_sistematico' => $this->post('nombre_sistematico'),
            'nombre_comun'       => $this->post('nombre_comun'),
            'uso'                => $this->post('uso'),
            'cotidiano'          => isset($_POST['cotidiano']) ? 1 : 0,
            'id_tipo'            => (int) $this->post('id_tipo', '0'),
        ];
    }

    private function validar(array $d, int $idExcluir): array
    {
        $errores = [];

        if (!Validator::requerido($d['formula']) || !Validator::formula($d['formula'])
            || !Validator::longitudMaxima($d['formula'], 30)) {
            $errores[] = 'La fórmula es obligatoria y solo puede tener letras, números y paréntesis.';
        } elseif ($this->modelo->existeFormula($d['formula'], $idExcluir)) {
            $errores[] = 'Ya existe un compuesto con esa fórmula.';
        }

        foreach (['nombre_tradicional' => 'tradicional', 'nombre_stock' => 'Stock', 'nombre_sistematico' => 'sistemático'] as $campo => $etiqueta) {
            if (!Validator::requerido($d[$campo]) || !Validator::nombreQuimico($d[$campo])
                || !Validator::longitudMaxima($d[$campo], 100)) {
                $errores[] = 'El nombre ' . $etiqueta . ' es obligatorio y solo puede tener letras, números, espacios y paréntesis.';
            }
        }

        if ($d['nombre_comun'] !== '' && (!Validator::nombreQuimico($d['nombre_comun']) || !Validator::longitudMaxima($d['nombre_comun'], 80))) {
            $errores[] = 'El nombre común tiene caracteres no permitidos o es demasiado largo.';
        }

        if ($d['cotidiano'] === 1 && $d['nombre_comun'] === '') {
            $errores[] = 'Para mostrarlo en la pantalla principal, el compuesto debe tener un nombre común.';
        }

        if (!Validator::longitudMaxima($d['uso'], 500)) {
            $errores[] = 'El campo "Para qué sirve" admite como máximo 500 caracteres.';
        }

        if ($d['id_tipo'] <= 0 || (new TipoCompuesto())->obtener($d['id_tipo']) === null) {
            $errores[] = 'Seleccione un tipo de compuesto válido.';
        }

        return $errores;
    }
}
