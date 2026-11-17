<h1>Evaluación</h1>
<p class="texto-gris">Elija el nivel con el que desea ser evaluado. Las preguntas se eligen al azar y su resultado se guarda en su historial.</p>

<div class="rejilla">
    <?php foreach ($niveles as $n): ?>
        <div class="tarjeta tarjeta-verde" style="display: flex; flex-direction: column;">
            <h2 class="texto-verde"><?= e($n['nombre']) ?></h2>
            <p class="texto-chico texto-gris" style="flex: 1;"><?= e($n['descripcion']) ?></p>
            <p class="texto-chico"><?= (int) $n['cantidad_preguntas'] ?> preguntas de opción múltiple</p>
            <a class="boton" href="<?= e(url('evaluacion', 'iniciar', ['nivel' => $n['id_nivel']])) ?>">Comenzar</a>
        </div>
    <?php endforeach; ?>
</div>
