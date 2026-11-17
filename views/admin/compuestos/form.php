<h1><?= e($titulo) ?></h1>

<?php foreach ($errores as $error): ?>
    <div class="aviso aviso-error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" class="tarjeta validar"
      action="<?= e($accion === 'editar' ? url('compuesto', 'editar', ['id' => $id]) : url('compuesto', 'crear')) ?>">
    <?= campo_csrf() ?>
    <div class="rejilla rejilla-2">
        <div class="campo">
            <label for="formula" class="etiqueta">Fórmula</label>
            <input type="text" class="entrada" id="formula" name="formula" required maxlength="30"
                   pattern="[A-Za-z0-9\(\)]+" value="<?= e($datos['formula']) ?>" placeholder="Ej.: CaO">
            <div class="mensaje-error">Obligatoria. Solo letras, números y paréntesis (sin espacios).</div>
        </div>
        <div class="campo">
            <label for="id_tipo" class="etiqueta">Tipo de compuesto</label>
            <select class="entrada" id="id_tipo" name="id_tipo" required>
                <option value="">Seleccione…</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= (int) $t['id_tipo'] ?>" <?= (int) $datos['id_tipo'] === (int) $t['id_tipo'] ? 'selected' : '' ?>><?= e($t['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="mensaje-error">Seleccione un tipo.</div>
        </div>
    </div>
    <div class="rejilla">
        <div class="campo">
            <label for="nombre_tradicional" class="etiqueta">Nombre tradicional</label>
            <input type="text" class="entrada" id="nombre_tradicional" name="nombre_tradicional" required maxlength="100"
                   value="<?= e($datos['nombre_tradicional']) ?>" placeholder="Ej.: Óxido cálcico">
            <div class="mensaje-error">Obligatorio.</div>
        </div>
        <div class="campo">
            <label for="nombre_stock" class="etiqueta">Nombre Stock</label>
            <input type="text" class="entrada" id="nombre_stock" name="nombre_stock" required maxlength="100"
                   value="<?= e($datos['nombre_stock']) ?>" placeholder="Ej.: Óxido de calcio">
            <div class="mensaje-error">Obligatorio.</div>
        </div>
        <div class="campo">
            <label for="nombre_sistematico" class="etiqueta">Nombre sistemático</label>
            <input type="text" class="entrada" id="nombre_sistematico" name="nombre_sistematico" required maxlength="100"
                   value="<?= e($datos['nombre_sistematico']) ?>" placeholder="Ej.: Monóxido de calcio">
            <div class="mensaje-error">Obligatorio.</div>
        </div>
    </div>
    <div class="rejilla rejilla-2">
        <div class="campo">
            <label for="nombre_comun" class="etiqueta">Nombre común (opcional)</label>
            <input type="text" class="entrada" id="nombre_comun" name="nombre_comun" maxlength="80"
                   value="<?= e($datos['nombre_comun']) ?>" placeholder="Ej.: Cal viva">
            <div class="mensaje-error">Máximo 80 caracteres.</div>
        </div>
        <div class="campo">
            <label for="uso" class="etiqueta">Para qué sirve (opcional)</label>
            <textarea class="entrada" id="uso" name="uso" rows="2" maxlength="500"><?= e($datos['uso']) ?></textarea>
        </div>
    </div>
    <div class="campo casilla">
        <input type="checkbox" id="cotidiano" name="cotidiano" value="1" <?= !empty($datos['cotidiano']) ? 'checked' : '' ?>>
        <label for="cotidiano">Mostrar en la pantalla principal (compuesto de la vida cotidiana; requiere nombre común)</label>
    </div>
    <div class="grupo-botones">
        <button type="submit" class="boton">Guardar</button>
        <a class="boton boton-gris" href="<?= e(url('compuesto', 'index')) ?>">Cancelar</a>
    </div>
</form>
