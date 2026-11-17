<?php /* Pie común de los temas: volver a la lista, tema anterior y tema siguiente. */ ?>
<div class="grupo-botones" style="justify-content: space-between; margin-top: 1.5rem;">
    <?php if ($anterior): ?>
        <a class="boton boton-borde" href="<?= e(url('aprende', 'tema', ['t' => $anterior['clave']])) ?>">← <?= e($anterior['titulo']) ?></a>
    <?php else: ?>
        <span></span>
    <?php endif; ?>
    <a class="boton boton-gris" href="<?= e(url('aprende', 'index')) ?>">Lista de temas</a>
    <?php if ($siguiente): ?>
        <a class="boton" href="<?= e(url('aprende', 'tema', ['t' => $siguiente['clave']])) ?>"><?= e($siguiente['titulo']) ?> →</a>
    <?php else: ?>
        <a class="boton" href="<?= e(url('formular', 'index')) ?>">Practicar en Formular →</a>
    <?php endif; ?>
</div>
