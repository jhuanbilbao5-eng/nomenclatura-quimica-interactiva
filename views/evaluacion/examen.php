<h1>Evaluación · Nivel <?= e($nivel['nombre']) ?></h1>
<p class="texto-chico texto-gris">Pregunta <span id="numeroActual">1</span> de <?= count($preguntas) ?></p>
<div class="progreso"><div class="progreso-barra" id="barraAvance"></div></div>

<form method="post" action="<?= e(url('evaluacion', 'calificar')) ?>" id="formExamen">
    <?= campo_csrf() ?>
    <?php foreach ($preguntas as $i => $p): ?>
        <div class="tarjeta pregunta separado <?= $i > 0 ? 'oculto' : '' ?>">
            <h2><?= e($p['enunciado']) ?></h2>
            <?php foreach (['A' => 'opcion_a', 'B' => 'opcion_b', 'C' => 'opcion_c', 'D' => 'opcion_d'] as $letra => $campo): ?>
                <label class="opcion" for="p<?= (int) $p['id_pregunta'] . $letra ?>">
                    <input type="radio" name="respuesta[<?= (int) $p['id_pregunta'] ?>]"
                           id="p<?= (int) $p['id_pregunta'] . $letra ?>" value="<?= $letra ?>">
                    <span><strong><?= $letra ?>)</strong> <?= e($p[$campo]) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="grupo-botones" style="justify-content: space-between;">
        <button type="button" class="boton boton-gris" id="btnAnterior" disabled>Anterior</button>
        <button type="button" class="boton" id="btnSiguiente">Siguiente</button>
        <button type="submit" class="boton boton-azul oculto" id="btnFinalizar">Finalizar evaluación</button>
    </div>
</form>

<script>
// Muestra una pregunta a la vez y avisa si quedan preguntas sin responder (JavaScript puro)
(function () {
    var preguntas = document.querySelectorAll('.pregunta');
    var actual = 0;
    var btnAnterior = document.getElementById('btnAnterior');
    var btnSiguiente = document.getElementById('btnSiguiente');
    var btnFinalizar = document.getElementById('btnFinalizar');

    function mostrar(indice) {
        preguntas.forEach(function (p, i) { p.classList.toggle('oculto', i !== indice); });
        document.getElementById('numeroActual').textContent = indice + 1;
        document.getElementById('barraAvance').style.width = ((indice + 1) / preguntas.length * 100) + '%';
        btnAnterior.disabled = indice === 0;
        var esUltima = indice === preguntas.length - 1;
        btnSiguiente.classList.toggle('oculto', esUltima);
        btnFinalizar.classList.toggle('oculto', !esUltima);
    }

    btnAnterior.addEventListener('click', function () { if (actual > 0) { actual--; mostrar(actual); } });
    btnSiguiente.addEventListener('click', function () { if (actual < preguntas.length - 1) { actual++; mostrar(actual); } });

    document.getElementById('formExamen').addEventListener('submit', function (ev) {
        var sinResponder = 0;
        preguntas.forEach(function (p) {
            if (!p.querySelector('input[type="radio"]:checked')) { sinResponder++; }
        });
        if (sinResponder > 0 && !confirm('Hay ' + sinResponder + ' pregunta(s) sin responder. ¿Desea enviar de todos modos?')) {
            ev.preventDefault();
        }
    });

    mostrar(0);
})();
</script>
