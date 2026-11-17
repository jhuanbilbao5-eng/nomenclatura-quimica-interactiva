<h1><?= e($titulo) ?></h1>

<?php foreach ($errores as $error): ?>
    <div class="aviso aviso-error"><?= e($error) ?></div>
<?php endforeach; ?>

<?php
$parametrosPdf = array_filter(
    ['nivel' => $filtros['nivel'] ?: '', 'desde' => $filtros['desde'], 'hasta' => $filtros['hasta']],
    fn($v) => $v !== ''
);
?>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="filtros filtros-4 validar" id="formFiltros">
    <input type="hidden" name="c" value="resultado">
    <input type="hidden" name="a" value="index">
    <div>
        <label for="nivel" class="etiqueta">Nivel</label>
        <select name="nivel" id="nivel" class="entrada">
            <option value="0">Todos</option>
            <?php foreach ($niveles as $n): ?>
                <option value="<?= (int) $n['id_nivel'] ?>" <?= $filtros['nivel'] === (int) $n['id_nivel'] ? 'selected' : '' ?>>
                    <?= e($n['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="desde" class="etiqueta">Desde</label>
        <input type="date" class="entrada" id="desde" name="desde" value="<?= e($filtros['desde']) ?>">
    </div>
    <div>
        <label for="hasta" class="etiqueta">Hasta</label>
        <input type="date" class="entrada" id="hasta" name="hasta" value="<?= e($filtros['hasta']) ?>"
               data-no-menor-que="#desde">
        <div class="mensaje-error">La fecha final no puede ser menor que la inicial.</div>
    </div>
    <div class="grupo-botones">
        <button type="submit" class="boton">Filtrar</button>
        <a class="boton boton-azul" href="<?= e(url('resultado', 'pdf', $parametrosPdf)) ?>" target="_blank">Reporte PDF</a>
    </div>
</form>

<?php if (count($resultados) > 0): ?>
    <?php
    $promedio = array_sum(array_map(fn($r) => (float) $r['nota'], $resultados)) / count($resultados);
    ?>
    <p class="texto-gris texto-chico">
        <?= count($resultados) ?> evaluación(es) · Nota promedio: <strong><?= e(number_format($promedio, 2)) ?></strong>
    </p>
<?php endif; ?>

<div class="tabla-contenedor">
    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <?php if (Auth::esAdmin()): ?><th>Usuario</th><?php endif; ?>
                <th>Nivel</th>
                <th class="texto-centro">Correctas</th>
                <th class="texto-centro">Nota</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($resultados) === 0): ?>
                <tr><td colspan="<?= Auth::esAdmin() ? 5 : 4 ?>" class="texto-centro texto-gris">No hay resultados para mostrar.</td></tr>
            <?php endif; ?>
            <?php foreach ($resultados as $r): ?>
                <tr>
                    <td><?= e(date('d/m/Y H:i', strtotime($r['fecha']))) ?></td>
                    <?php if (Auth::esAdmin()): ?><td><?= e($r['usuario']) ?></td><?php endif; ?>
                    <td><?= e($r['nivel']) ?></td>
                    <td class="texto-centro"><?= (int) $r['correctas'] ?> de <?= (int) $r['total_preguntas'] ?></td>
                    <td class="texto-centro negrita"><?= e(number_format((float) $r['nota'], 2)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
