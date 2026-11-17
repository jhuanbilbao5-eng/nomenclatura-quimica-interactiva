<div class="encabezado">
    <h1>Preguntas de evaluación</h1>
    <a class="boton" href="<?= e(url('pregunta', 'crear')) ?>">+ Nueva pregunta</a>
</div>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="filtros filtros-4">
    <input type="hidden" name="c" value="pregunta">
    <input type="hidden" name="a" value="index">
    <div>
        <label for="q" class="etiqueta">Buscar</label>
        <input type="text" class="entrada" id="q" name="q" maxlength="100" value="<?= e($texto) ?>" placeholder="Texto del enunciado">
    </div>
    <div>
        <label for="nivel" class="etiqueta">Nivel</label>
        <select name="nivel" id="nivel" class="entrada">
            <option value="0">Todos</option>
            <?php foreach ($niveles as $n): ?>
                <option value="<?= (int) $n['id_nivel'] ?>" <?= $idNivel === (int) $n['id_nivel'] ? 'selected' : '' ?>><?= e($n['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="tipo" class="etiqueta">Tipo</label>
        <select name="tipo" id="tipo" class="entrada">
            <option value="0">Todos</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= (int) $t['id_tipo'] ?>" <?= $idTipo === (int) $t['id_tipo'] ? 'selected' : '' ?>><?= e($t['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="boton">Filtrar</button>
</form>

<p class="texto-gris texto-chico"><?= count($preguntas) ?> registro(s).</p>

<div class="tabla-contenedor">
    <table class="tabla">
        <thead>
            <tr><th>Enunciado</th><th>Nivel</th><th>Tipo</th><th class="texto-centro">Correcta</th><th>Estado</th><th class="texto-derecha">Acciones</th></tr>
        </thead>
        <tbody>
            <?php if (count($preguntas) === 0): ?>
                <tr><td colspan="6" class="texto-centro texto-gris">No se encontraron preguntas.</td></tr>
            <?php endif; ?>
            <?php foreach ($preguntas as $p): ?>
                <tr>
                    <td><?= e($p['enunciado']) ?></td>
                    <td><?= e($p['nivel']) ?></td>
                    <td><?= e($p['tipo']) ?></td>
                    <td class="texto-centro negrita"><?= e($p['respuesta_correcta']) ?></td>
                    <td>
                        <?php if ($p['estado']): ?><span class="insignia">Activa</span>
                        <?php else: ?><span class="insignia insignia-gris">Inactiva</span><?php endif; ?>
                    </td>
                    <td class="texto-derecha sin-salto">
                        <a class="boton boton-azul boton-chico" href="<?= e(url('pregunta', 'ver', ['id' => $p['id_pregunta']])) ?>">Ver</a>
                        <a class="boton boton-chico" href="<?= e(url('pregunta', 'editar', ['id' => $p['id_pregunta']])) ?>">Editar</a>
                        <form method="post" class="en-linea"
                              action="<?= e(url('pregunta', $p['estado'] ? 'desactivar' : 'activar')) ?>"
                              data-confirmar="¿Confirma el cambio de estado de esta pregunta?">
                            <?= campo_csrf() ?>
                            <input type="hidden" name="id" value="<?= (int) $p['id_pregunta'] ?>">
                            <button type="submit" class="boton boton-chico <?= $p['estado'] ? 'boton-rojo' : 'boton-gris' ?>">
                                <?= $p['estado'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
