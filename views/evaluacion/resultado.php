<?php
$letras = ['A' => 'opcion_a', 'B' => 'opcion_b', 'C' => 'opcion_c', 'D' => 'opcion_d'];
$nota = (float) $evaluacion['nota'];
if ($nota >= 80) {
    $mensaje = '¡Excelente trabajo!';
} elseif ($nota >= 51) {
    $mensaje = 'Aprobado. Sigue practicando para mejorar.';
} else {
    $mensaje = 'Te recomendamos repasar los temas en Aprende Química.';
}
?>
<h1>Resultado de la evaluación</h1>

<div class="rejilla" style="grid-template-columns: 1fr 2fr; align-items: start;" id="resultadoRejilla">
    <div class="tarjeta tarjeta-verde texto-centro">
        <p class="texto-gris" style="margin-bottom: .2rem;">Tu nota</p>
        <p class="cifra"><?= e(number_format($nota, 0)) ?> / 100</p>
        <p class="negrita"><?= e($mensaje) ?></p>
        <p><?= (int) $evaluacion['correctas'] ?> respuestas correctas de <?= (int) $evaluacion['total_preguntas'] ?></p>
        <p class="texto-chico texto-gris" style="margin: 0;">
            Nivel: <?= e($evaluacion['nivel']) ?> · <?= e(date('d/m/Y H:i', strtotime($evaluacion['fecha']))) ?>
        </p>
    </div>
    <div>
        <h2>Revisión de respuestas</h2>
        <?php foreach ($detalle as $i => $d): ?>
            <div class="revision <?= $d['es_correcta'] ? 'revision-bien' : 'revision-mal' ?>">
                <div class="negrita"><?= $d['es_correcta'] ? '✔' : '✘' ?> Pregunta <?= $i + 1 ?>: <?= e($d['enunciado']) ?></div>
                <div class="texto-chico">
                    Tu respuesta:
                    <?php if ($d['respuesta_usuario'] === null): ?>
                        sin responder
                    <?php else: ?>
                        <?= e($d['respuesta_usuario']) ?>) <?= e($d[$letras[$d['respuesta_usuario']]]) ?>
                    <?php endif; ?>
                    <?php if (!$d['es_correcta']): ?>
                        · Correcta: <?= e($d['respuesta_correcta']) ?>) <?= e($d[$letras[$d['respuesta_correcta']]]) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="grupo-botones" style="margin-top: 1.5rem;">
    <a class="boton" href="<?= e(url('evaluacion', 'iniciar', ['nivel' => $evaluacion['id_nivel']])) ?>">Repetir nivel</a>
    <a class="boton boton-borde" href="<?= e(url('evaluacion', 'index')) ?>">Elegir otro nivel</a>
    <a class="boton boton-borde" href="<?= e(url('resultado', 'index')) ?>">Mi historial</a>
</div>

<style>
    @media (max-width: 600px) { #resultadoRejilla { grid-template-columns: 1fr !important; } }
</style>
