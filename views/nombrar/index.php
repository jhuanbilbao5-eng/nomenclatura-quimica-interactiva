<h1>Nombrar: del nombre a la fórmula</h1>
<p class="texto-gris">Escriba el nombre de un compuesto (tradicional, Stock, sistemático o común) y el sistema mostrará su fórmula.</p>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="tarjeta validar separado">
    <input type="hidden" name="c" value="nombrar">
    <input type="hidden" name="a" value="index">
    <div class="campo">
        <label for="nombre" class="etiqueta">Nombre del compuesto (no importan las mayúsculas ni las tildes)</label>
        <input type="text" class="entrada" id="nombre" name="nombre" required maxlength="100"
               pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\s\-\(\),\.]+"
               placeholder="Ej.: cloruro de sodio, sal de mesa, óxido de hierro (III)" value="<?= e($entrada) ?>">
        <div class="mensaje-error">Ingrese un nombre. Solo letras, números, espacios y paréntesis.</div>
    </div>
    <button type="submit" class="boton">Buscar fórmula</button>
</form>

<?php if ($error !== ''): ?>
    <div class="aviso aviso-error"><?= e($error) ?></div>
<?php elseif ($buscado && $compuesto === null): ?>
    <div class="aviso aviso-advertencia">Compuesto no registrado. Revise que el nombre esté completo (por ejemplo: <strong>óxido de hierro (III)</strong>).</div>
<?php elseif ($compuesto !== null): ?>
    <div class="tarjeta tarjeta-verde">
        <h2 class="texto-verde">Resultado</h2>
        <dl class="datos">
            <dt>Fórmula</dt>
            <dd class="formula" style="font-size: 1.8rem;"><?= formula_html($compuesto['formula']) ?></dd>
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
        </dl>
    </div>
<?php endif; ?>
