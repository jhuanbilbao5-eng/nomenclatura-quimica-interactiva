<?php
// Módulo Nombrar: del nombre a la fórmula (RF05, RF06)

class NombrarController extends Controller
{
    public function __construct()
    {
        Auth::requerirLogin();
    }

    /** P07: busca el compuesto cuyo nombre (o nombre común) coincide con el ingresado. */
    public function index(): void
    {
        $entrada = $this->get('nombre');
        $buscado = false;
        $error = '';
        $compuesto = null;

        if ($entrada !== '') {
            $buscado = true;
            if (!Validator::nombreQuimico($entrada) || !Validator::longitudMaxima($entrada, 100)) {
                $error = 'El nombre solo puede tener letras, números, espacios y paréntesis.';
            } else {
                $compuesto = (new Compuesto())->buscarPorNombre($entrada);
            }
        }

        $this->vista('nombrar/index', [
            'titulo'    => 'Nombrar',
            'entrada'   => $entrada,
            'buscado'   => $buscado,
            'error'     => $error,
            'compuesto' => $compuesto,
        ]);
    }
}
