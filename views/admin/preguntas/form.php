<h1><?= e($titulo) ?></h1>

<?php foreach ($errores as $error): ?>
    <div class="aviso aviso-error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" class="tarjeta validar"
      action="<?= e($accion === 'editar' ? url('pregunta', 'editar', ['id' => $id]) : url('pregunta', 'crear')) ?>">
    <?= campo_csrf() ?>
    <div class="rejilla rejilla-2">
        <div class="campo">
            <label for="id_nivel" class="etiqueta">Nivel</label>
            <select class="entrada" id="id_nivel" name="id_nivel" required>
                <option value="">Seleccione…</option>
                <?php foreach ($niveles as $n): ?>
                    <option value="<?= (int) $n['id_nivel'] ?>" <?= (int) $datos['id_nivel'] === (int) $n['id_nivel'] ? 'selected' : '' ?>><?= e($n['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="mensaje-error">Seleccione un nivel.</div>
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
    <div class="campo">
        <label for="enunciado" class="etiqueta">Enunciado</label>
        <input type="text" class="entrada" id="enunciado" name="enunciado" required maxlength="255"
               value="<?= e($datos['enunciado']) ?>" placeholder="Ej.: ¿Cuál es la fórmula del óxido de calcio?">
        <div class="mensaje-error">Obligatorio (máximo 255 caracteres).</div>
    </div>
    <div class="rejilla rejilla-2">
        <?php foreach (['A' => 'opcion_a', 'B' => 'opcion_b', 'C' => 'opcion_c', 'D' => 'opcion_d'] as $letra => $campo): ?>
            <div class="campo">
                <label for="<?= $campo ?>" class="etiqueta">Opción <?= $letra ?></label>
                <input type="text" class="entrada" id="<?= $campo ?>" name="<?= $campo ?>" required maxlength="100"
                       data-opcion value="<?= e($datos[$campo]) ?>">
                <div class="mensaje-error">Obligatoria y distinta de las demás.</div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="campo" style="max-width: 260px;">
        <label for="respuesta_correcta" class="etiqueta">Respuesta correcta</label>
        <select class="entrada" id="respuesta_correcta" name="respuesta_correcta" required>
            <option value="">Seleccione…</option>
            <?php foreach (['A', 'B', 'C', 'D'] as $letra): ?>
                <option value="<?= $letra ?>" <?= $datos['respuesta_correcta'] === $letra ? 'selected' : '' ?>><?= $letra ?></option>
            <?php endforeach; ?>
        </select>
        <div class="mensaje-error">Elija la respuesta correcta.</div>
    </div>
    <div class="grupo-botones">
        <button type="submit" class="boton">Guardar</button>
        <a class="boton boton-gris" href="<?= e(url('pregunta', 'index')) ?>">Cancelar</a>
    </div>
</form>
