<?php
// Módulo Aprende Química: contenido estático (sin consultas a la base de datos) - RF01

class AprendeController extends Controller
{
    /** Temas disponibles: clave de la página => título. */
    private array $temas = [
        'introduccion' => 'Introducción: qué es la nomenclatura y valencias',
        'oxidos'       => 'Óxidos',
        'hidruros'     => 'Hidruros',
        'hidroxidos'   => 'Hidróxidos',
        'acidos'       => 'Ácidos',
        'sales'        => 'Sales (neutras, ácidas, básicas y mixtas)',
    ];

    public function __construct()
    {
        Auth::requerirLogin();
    }

    /** P04: lista de temas. */
    public function index(): void
    {
        $this->vista('aprende/index', ['titulo' => 'Aprende Química', 'temas' => $this->temas]);
    }

    /** P05: página estática de un tema (aprende/temas/<tema>.php). */
    public function tema(): void
    {
        $clave = $this->get('t');
        if (!isset($this->temas[$clave])) {
            $this->flash('danger', 'El tema solicitado no existe.');
            redirigir('aprende', 'index');
        }

        // Tema anterior y siguiente para navegar en orden
        $claves = array_keys($this->temas);
        $posicion = array_search($clave, $claves, true);
        $anterior = $posicion > 0 ? $claves[$posicion - 1] : null;
        $siguiente = $posicion < count($claves) - 1 ? $claves[$posicion + 1] : null;

        $this->vista('aprende/temas/' . $clave, [
            'titulo'     => $this->temas[$clave],
            'tituloTema' => $this->temas[$clave],
            'anterior'   => $anterior !== null ? ['clave' => $anterior, 'titulo' => $this->temas[$anterior]] : null,
            'siguiente'  => $siguiente !== null ? ['clave' => $siguiente, 'titulo' => $this->temas[$siguiente]] : null,
        ]);
    }
}
