<?php
// Pantalla principal con el repositorio de compuestos cotidianos (RF12) y panel del administrador

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::requerirLogin();
    }

    /** P03: menú de módulos y repositorio de compuestos de la vida cotidiana. */
    public function index(): void
    {
        $texto = $this->get('q');
        $idTipo = (int) $this->get('tipo', '0');

        $this->vista('home/index', [
            'titulo'      => 'Inicio',
            'texto'       => $texto,
            'idTipo'      => $idTipo,
            'tipos'       => (new TipoCompuesto())->listar(),
            'cotidianos'  => (new Compuesto())->listarCotidianos($texto, $idTipo),
        ]);
    }

    /** P12: panel de administración (solo Administrador). */
    public function panel(): void
    {
        Auth::requerirRol('Administrador');

        $this->vista('admin/panel', [
            'titulo'        => 'Panel de administración',
            'totalCompuestos'  => (new Compuesto())->contar(),
            'totalPreguntas'   => (new Pregunta())->contar(),
            'totalEvaluaciones' => (new Evaluacion())->contar(),
            'totalUsuarios'     => (new Usuario())->contarEstudiantes(),
        ]);
    }
}
