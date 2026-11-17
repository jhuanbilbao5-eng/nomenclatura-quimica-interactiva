<?php
// Historial de resultados con filtros y reporte PDF (RF13)
// El Usuario ve solo sus resultados; el Administrador ve los de todos.

class ResultadoController extends Controller
{
    public function __construct()
    {
        Auth::requerirLogin();
    }

    /** P11: historial con filtros por nivel y fecha. */
    public function index(): void
    {
        $f = $this->filtros();
        $errores = $this->validarFiltros($f);

        $resultados = [];
        if (count($errores) === 0) {
            $resultados = (new Evaluacion())->listar($this->idUsuarioConsulta(), $f['nivel'], $f['desde'], $f['hasta']);
        }

        $this->vista('resultados/index', [
            'titulo'     => Auth::esAdmin() ? 'Resultados de todos los usuarios' : 'Mis resultados',
            'filtros'    => $f,
            'errores'    => $errores,
            'niveles'    => (new Nivel())->listar(),
            'resultados' => $resultados,
        ]);
    }

    /** Genera el reporte PDF con la librería FPDF (se debe copiar en libs/fpdf/). */
    public function pdf(): void
    {
        $f = $this->filtros();
        if (count($this->validarFiltros($f)) > 0) {
            $this->flash('danger', 'Revise los filtros antes de generar el reporte.');
            redirigir('resultado', 'index');
        }

        $archivo = LIBS_PATH . '/fpdf/fpdf.php';
        if (!is_file($archivo)) {
            $this->flash('warning', 'Falta la librería FPDF. Copie fpdf.php en libs/fpdf/ (ver libs/fpdf/LEEME.txt).');
            redirigir('resultado', 'index', $this->parametrosFiltro($f));
        }
        require_once $archivo;

        $esAdmin = Auth::esAdmin();
        $resultados = (new Evaluacion())->listar($this->idUsuarioConsulta(), $f['nivel'], $f['desde'], $f['hasta']);

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, $this->texto(APP_NOMBRE), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, $this->texto($esAdmin ? 'Reporte de resultados de todos los usuarios' : 'Reporte de mis resultados'), 0, 1, 'C');
        $pdf->Cell(0, 6, $this->texto('Generado el ' . date('d/m/Y H:i') . ' por ' . Auth::usuario()['nombre']), 0, 1, 'C');

        // Filtros aplicados
        $nivel = $f['nivel'] > 0 ? (new Nivel())->obtener($f['nivel']) : null;
        $textoFiltros = 'Nivel: ' . ($nivel ? $nivel['nombre'] : 'todos')
            . '   Desde: ' . ($f['desde'] !== '' ? date('d/m/Y', strtotime($f['desde'])) : '-')
            . '   Hasta: ' . ($f['hasta'] !== '' ? date('d/m/Y', strtotime($f['hasta'])) : '-');
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(0, 6, $this->texto($textoFiltros), 0, 1, 'C');
        $pdf->Ln(4);

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(27, 111, 92);
        $pdf->SetTextColor(255, 255, 255);
        if ($esAdmin) {
            $pdf->Cell(38, 8, 'Fecha', 1, 0, 'C', true);
            $pdf->Cell(62, 8, 'Usuario', 1, 0, 'C', true);
            $pdf->Cell(28, 8, 'Nivel', 1, 0, 'C', true);
            $pdf->Cell(32, 8, 'Correctas', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'Nota', 1, 1, 'C', true);
        } else {
            $pdf->Cell(50, 8, 'Fecha', 1, 0, 'C', true);
            $pdf->Cell(45, 8, 'Nivel', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'Correctas', 1, 0, 'C', true);
            $pdf->Cell(35, 8, 'Nota', 1, 1, 'C', true);
        }

        // Filas
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        foreach ($resultados as $r) {
            $fecha = date('d/m/Y H:i', strtotime($r['fecha']));
            $correctas = $r['correctas'] . ' de ' . $r['total_preguntas'];
            if ($esAdmin) {
                $pdf->Cell(38, 7, $fecha, 1);
                $pdf->Cell(62, 7, $this->texto($r['usuario']), 1);
                $pdf->Cell(28, 7, $this->texto($r['nivel']), 1);
                $pdf->Cell(32, 7, $correctas, 1, 0, 'C');
                $pdf->Cell(25, 7, number_format((float) $r['nota'], 2), 1, 1, 'C');
            } else {
                $pdf->Cell(50, 7, $fecha, 1);
                $pdf->Cell(45, 7, $this->texto($r['nivel']), 1);
                $pdf->Cell(50, 7, $correctas, 1, 0, 'C');
                $pdf->Cell(35, 7, number_format((float) $r['nota'], 2), 1, 1, 'C');
            }
        }
        if (count($resultados) === 0) {
            $pdf->Cell(0, 8, $this->texto('No hay resultados para los filtros seleccionados.'), 1, 1, 'C');
        } else {
            // Resumen: cantidad de evaluaciones y nota promedio
            $promedio = array_sum(array_map(fn($r) => (float) $r['nota'], $resultados)) / count($resultados);
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, $this->texto('Total de evaluaciones: ' . count($resultados)
                . '     Nota promedio: ' . number_format($promedio, 2)), 0, 1, 'R');
        }

        $pdf->Output('I', 'reporte_resultados.pdf');
        exit;
    }

    // ---- Auxiliares ----

    /** Administrador: 0 (todos). Usuario: su propio id. */
    private function idUsuarioConsulta(): int
    {
        return Auth::esAdmin() ? 0 : (int) Auth::id();
    }

    private function filtros(): array
    {
        return [
            'nivel' => (int) $this->get('nivel', '0'),
            'desde' => $this->get('desde'),
            'hasta' => $this->get('hasta'),
        ];
    }

    private function validarFiltros(array $f): array
    {
        $errores = [];
        if ($f['desde'] !== '' && !Validator::fecha($f['desde'])) {
            $errores[] = 'La fecha inicial no es válida.';
        }
        if ($f['hasta'] !== '' && !Validator::fecha($f['hasta'])) {
            $errores[] = 'La fecha final no es válida.';
        }
        if (count($errores) === 0 && $f['desde'] !== '' && $f['hasta'] !== '' && $f['desde'] > $f['hasta']) {
            $errores[] = 'La fecha inicial no puede ser mayor que la final.';
        }
        return $errores;
    }

    private function parametrosFiltro(array $f): array
    {
        return array_filter(['nivel' => $f['nivel'] ?: '', 'desde' => $f['desde'], 'hasta' => $f['hasta']], fn($v) => $v !== '');
    }

    /** FPDF trabaja con ISO-8859-1/Windows-1252: se convierte el texto con tildes. */
    private function texto(string $texto): string
    {
        $convertido = iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
        return $convertido !== false ? $convertido : $texto;
    }
}
