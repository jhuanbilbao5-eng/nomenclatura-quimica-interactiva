<?php
// Cabecera común: menú según el rol y mensajes (flash).
// HTML y CSS propios: el proyecto NO usa frameworks (ni Bootstrap ni otros).
$usuarioSesion = Auth::usuario();
$mensajeFlash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$tituloPagina = (isset($titulo) && $titulo !== '') ? $titulo . ' · ' . APP_NOMBRE : APP_NOMBRE;
$controladorActual = strtolower($_GET['c'] ?? '');

// Traduce el tipo de mensaje a la clase CSS propia
$clasesAviso = ['success' => 'aviso-exito', 'danger' => 'aviso-error', 'warning' => 'aviso-advertencia', 'info' => 'aviso-info'];

/** Marca como activo el enlace del módulo en el que se encuentra el usuario. */
$activo = function (string $c) use ($controladorActual): string {
    return $controladorActual === $c ? ' class="activo"' : '';
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($tituloPagina) ?></title>
    <link href="<?= e(BASE_URL) ?>css/estilos.css" rel="stylesheet">
</head>
<body>
<header class="barra">
    <div class="contenedor">
        <a class="barra-marca" href="<?= e(BASE_URL) ?>">⚗ <?= e(APP_NOMBRE) ?></a>
        <?php if ($usuarioSesion): ?>
            <button class="barra-boton" type="button" id="botonMenu" aria-controls="menuPrincipal"
                    aria-expanded="false" aria-label="Mostrar menú">☰</button>
            <nav class="barra-menu" id="menuPrincipal">
                <ul class="barra-enlaces">
                    <?php if (Auth::esAdmin()): ?>
                        <li><a href="<?= e(url('home', 'panel')) ?>"<?= $activo('home') ?>>Panel</a></li>
                        <li><a href="<?= e(url('compuesto', 'index')) ?>"<?= $activo('compuesto') ?>>Compuestos</a></li>
                        <li><a href="<?= e(url('pregunta', 'index')) ?>"<?= $activo('pregunta') ?>>Preguntas</a></li>
                        <li><a href="<?= e(url('resultado', 'index')) ?>"<?= $activo('resultado') ?>>Resultados</a></li>
                    <?php else: ?>
                        <li><a href="<?= e(url('home', 'index')) ?>"<?= $activo('home') ?>>Inicio</a></li>
                        <li><a href="<?= e(url('evaluacion', 'index')) ?>"<?= $activo('evaluacion') ?>>Evaluación</a></li>
                        <li><a href="<?= e(url('resultado', 'index')) ?>"<?= $activo('resultado') ?>>Mis resultados</a></li>
                    <?php endif; ?>
                    <li><a href="<?= e(url('aprende', 'index')) ?>"<?= $activo('aprende') ?>>Aprende</a></li>
                    <li><a href="<?= e(url('formular', 'index')) ?>"<?= $activo('formular') ?>>Formular</a></li>
                    <li><a href="<?= e(url('nombrar', 'index')) ?>"<?= $activo('nombrar') ?>>Nombrar</a></li>
                </ul>
                <a class="barra-usuario" href="<?= e(url('auth', 'cuenta')) ?>" title="Mi cuenta: cambiar contraseña">
                    👤 <?= e($usuarioSesion['nombre']) ?> (<?= e($usuarioSesion['rol']) ?>)
                </a>
                <a class="boton boton-borde-claro" href="<?= e(url('auth', 'logout')) ?>">Cerrar sesión</a>
            </nav>
        <?php endif; ?>
    </div>
</header>

<main class="contenido contenedor">
    <?php if ($mensajeFlash): ?>
        <div class="aviso <?= e($clasesAviso[$mensajeFlash['tipo']] ?? 'aviso-info') ?>" role="alert">
            <?= e($mensajeFlash['mensaje']) ?>
            <button type="button" class="aviso-cerrar" aria-label="Cerrar">&times;</button>
        </div>
    <?php endif; ?>
