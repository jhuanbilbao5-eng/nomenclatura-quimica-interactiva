<h1>Formular: de la fórmula al nombre</h1>
<p class="texto-gris">Escriba la fórmula de un compuesto y el sistema mostrará su nombre en las tres nomenclaturas.</p>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="tarjeta validar separado">
    <input type="hidden" name="c" value="formular">
    <input type="hidden" name="a" value="index">
    <div class="campo">
        <label for="formula" class="etiqueta">Fórmula (respete mayúsculas y minúsculas: CO no es lo mismo que Co)</label>
        <input type="text" class="entrada" id="formula" name="formula" required maxlength="30"
               pattern="[A-Za-z0-9\(\) ]+" placeholder="Ej.: Fe2O3, NaOH, H2SO4" value="<?= e($entrada) ?>">
        <div class="mensaje-error">Ingrese una fórmula. Solo letras, números y paréntesis.</div>
    </div>
    <button type="submit" class="boton">Buscar nombre</button>
</form>

<?php if ($error !== ''): ?>
    <div class="aviso aviso-error"><?= e($error) ?></div>
<?php elseif ($buscado && $compuesto === null): ?>
    <div class="aviso aviso-advertencia">
        Compuesto no registrado. Revise que las mayúsculas y los números sean correctos (por ejemplo: <strong>CaO</strong>, no <strong>cao</strong>).
    </div>
<?php elseif ($compuesto !== null): ?>
    <div class="tarjeta tarjeta-verde">
        <h2 class="texto-verde">Resultado</h2>
        <dl class="datos">
            <dt>Fórmula</dt>
            <dd class="formula"><?= formula_html($compuesto['formula']) ?></dd>
            <dt>Tipo de compuesto</dt>
            <dd><?= e($compuesto['tipo']) ?></dd>
            <dt>Nomenclatura tradicional</dt>
            <dd><?= e($compuesto['nombre_tradicional']) ?></dd>
            <dt>Nomenclatura Stock</dt>
            <dd><?= e($compuesto['nombre_stock']) ?></dd>
            <dt>Nomenclatura sistemática</dt>
            <dd><?= e($compuesto['nombre_sistematico']) ?></dd>
            <?php if ($compuesto['nombre_comun']): ?>
                <dt>Nombre común</dt>
                <dd><?= e($compuesto['nombre_comun']) ?></dd>
            <?php endif; ?>
            <?php if ($compuesto['uso']): ?>
                <dt>Para qué sirve</dt>
                <dd><?= e($compuesto['uso']) ?></dd>
            <?php endif; ?>
        </dl>
    </div>
<?php endif; ?>
