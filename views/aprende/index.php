<h1>Aprende Química</h1>
<p class="texto-gris">Elija un tema para estudiar. Cada tema explica qué es, cómo se formula, cómo se nombra, cómo se forma por reacción y presenta ejemplos resueltos. Se recomienda seguir el orden.</p>

<div class="rejilla">
    <?php $numero = 0; foreach ($temas as $clave => $nombre): $numero++; ?>
        <a class="tarjeta tarjeta-verde" href="<?= e(url('aprende', 'tema', ['t' => $clave])) ?>">
            <span class="insignia">Tema <?= $numero ?></span>
            <h2 class="texto-verde" style="margin: .6rem 0 0;"><?= e($nombre) ?></h2>
        </a>
    <?php endforeach; ?>
</div>
