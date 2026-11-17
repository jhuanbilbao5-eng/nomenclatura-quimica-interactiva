<div class="encabezado">
    <h1>Consultar pregunta</h1>
    <div class="grupo-botones">
        <a class="boton" href="<?= e(url('pregunta', 'editar', ['id' => $pregunta['id_pregunta']])) ?>">Editar</a>
        <a class="boton boton-gris" href="<?= e(url('pregunta', 'index')) ?>">Volver al listado</a>
    </div>
</div>

<div class="tarjeta">
    <p class="texto-gris texto-chico">
        Nivel: <span class="insignia"><?= e($pregunta['nivel']) ?></span>
        · Tipo: <span class="insignia"><?= e($pregunta['tipo']) ?></span>
        · Estado: <?= $pregunta['estado'] ? '<span class="insignia">Activa</span>' : '<span class="insignia insignia-gris">Inactiva</span>' ?>
    </p>
    <h2><?= e($pregunta['enunciado']) ?></h2>
    <?php foreach (['A' => 'opcion_a', 'B' => 'opcion_b', 'C' => 'opcion_c', 'D' => 'opcion_d'] as $letra => $campo): ?>
        <div class="revision <?= $pregunta['respuesta_correcta'] === $letra ? 'revision-bien' : '' ?>">
            <strong><?= $letra ?>)</strong> <?= e($pregunta[$campo]) ?>
            <?php if ($pregunta['respuesta_correcta'] === $letra): ?> <span class="insignia">Correcta</span><?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
