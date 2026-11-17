<h1>Panel de administración</h1>
<p class="texto-gris">Resumen de la plataforma y accesos a la gestión de datos.</p>

<div class="rejilla rejilla-4 separado">
    <div class="tarjeta tarjeta-verde texto-centro">
        <p class="cifra"><?= (int) $totalCompuestos ?></p>
        <p class="texto-gris" style="margin: 0;">Compuestos activos</p>
    </div>
    <div class="tarjeta tarjeta-verde texto-centro">
        <p class="cifra"><?= (int) $totalPreguntas ?></p>
        <p class="texto-gris" style="margin: 0;">Preguntas activas</p>
    </div>
    <div class="tarjeta tarjeta-verde texto-centro">
        <p class="cifra"><?= (int) $totalEvaluaciones ?></p>
        <p class="texto-gris" style="margin: 0;">Evaluaciones realizadas</p>
    </div>
    <div class="tarjeta tarjeta-verde texto-centro">
        <p class="cifra"><?= (int) $totalUsuarios ?></p>
        <p class="texto-gris" style="margin: 0;">Estudiantes registrados</p>
    </div>
</div>

<div class="rejilla">
    <a class="boton boton-grande" href="<?= e(url('compuesto', 'index')) ?>">Gestionar compuestos</a>
    <a class="boton boton-grande" href="<?= e(url('pregunta', 'index')) ?>">Gestionar preguntas</a>
    <a class="boton boton-grande" href="<?= e(url('resultado', 'index')) ?>">Ver resultados y reporte PDF</a>
</div>

<h2 class="texto-verde" style="margin-top: 2rem;">Módulos de consulta</h2>
<div class="rejilla">
    <a class="boton boton-borde" href="<?= e(url('home', 'index')) ?>">Repositorio de compuestos</a>
    <a class="boton boton-borde" href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a>
    <a class="boton boton-borde" href="<?= e(url('formular', 'index')) ?>">Formular / Nombrar</a>
</div>
