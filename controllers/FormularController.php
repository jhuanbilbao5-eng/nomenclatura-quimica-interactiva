<?php
// Módulo Formular: de la fórmula al nombre (RF02, RF03, RF04)

class FormularController extends Controller
{
    public function __construct()
    {
        Auth::requerirLogin();
    }

    /** P06: busca el compuesto cuya fórmula coincide con la ingresada. */
    public function index(): void
    {
        $entrada = $this->get('formula');
        $formula = preg_replace('/\s+/', '', $entrada);
        $buscado = false;
        $error = '';
        $compuesto = null;

        if ($entrada !== '') {
            $buscado = true;
            if (!Validator::formula($formula) || !Validator::longitudMaxima($formula, 30)) {
                $error = 'La fórmula solo puede tener letras, números y paréntesis.';
            } else {
                $compuesto = (new Compuesto())->buscarPorFormula($formula);
            }
        }

        $this->vista('formular/index', [
            'titulo'    => 'Formular',
            'entrada'   => $entrada,
            'buscado'   => $buscado,
            'error'     => $error,
            'compuesto' => $compuesto,
        ]);
    }
}
