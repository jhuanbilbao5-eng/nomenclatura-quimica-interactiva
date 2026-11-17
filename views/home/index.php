<h1>Pantalla principal</h1>
<p class="texto-gris">Bienvenido(a), <?= e(Auth::usuario()['nombre']) ?>. Elija un módulo para comenzar.</p>

<!-- Acceso a los cuatro módulos -->
<div class="rejilla rejilla-4 separado">
    <a class="boton boton-grande" href="<?= e(url('aprende', 'index')) ?>">📘 Aprende Química</a>
    <a class="boton boton-grande" href="<?= e(url('formular', 'index')) ?>">🧪 Formular</a>
    <a class="boton boton-grande" href="<?= e(url('nombrar', 'index')) ?>">🏷 Nombrar</a>
    <?php if (!Auth::esAdmin()): ?>
        <a class="boton boton-grande" href="<?= e(url('evaluacion', 'index')) ?>">📝 Evaluación</a>
    <?php else: ?>
        <a class="boton boton-grande boton-gris" href="<?= e(url('home', 'panel')) ?>">⚙ Panel de administración</a>
    <?php endif; ?>
</div>

<!-- Repositorio de compuestos de la vida cotidiana (RF12) -->
<h2 class="texto-verde">Compuestos de la vida cotidiana</h2>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="filtros">
    <input type="hidden" name="c" value="home">
    <input type="hidden" name="a" value="index">
    <div>
        <label for="q" class="etiqueta">Buscar</label>
        <input type="text" class="entrada" id="q" name="q" maxlength="100" value="<?= e($texto) ?>"
               placeholder="Nombre común, fórmula o nombre">
    </div>
    <div>
        <label for="tipo" class="etiqueta">Tipo</label>
        <select name="tipo" id="tipo" class="entrada">
            <option value="0">Todos</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= (int) $t['id_tipo'] ?>" <?= $idTipo === (int) $t['id_tipo'] ? 'selected' : '' ?>>
                    <?= e($t['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="boton">Buscar</button>
</form>

<?php if (count($cotidianos) === 0): ?>
    <div class="aviso aviso-info">No se encontraron compuestos con esos criterios.</div>
<?php else: ?>
    <p class="texto-gris texto-chico"><?= count($cotidianos) ?> compuesto(s) encontrado(s).</p>
    <div class="rejilla">
        <?php foreach ($cotidianos as $c): ?>
            <div class="tarjeta tarjeta-verde">
                <span class="insignia"><?= e($c['tipo']) ?></span>
                <h3 style="margin-top: .6rem;"><?= e($c['nombre_comun']) ?></h3>
                <p class="formula"><?= formula_html($c['formula']) ?></p>
                <p class="texto-chico texto-gris" style="margin-bottom: .3rem;"><?= e($c['nombre_stock']) ?></p>
                <p class="texto-chico" style="margin-bottom: 0;"><?= e($c['uso']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
