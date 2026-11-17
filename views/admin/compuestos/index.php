<div class="encabezado">
    <h1>Compuestos</h1>
    <a class="boton" href="<?= e(url('compuesto', 'crear')) ?>">+ Nuevo compuesto</a>
</div>

<form method="get" action="<?= e(BASE_URL) ?>index.php" class="filtros">
    <input type="hidden" name="c" value="compuesto">
    <input type="hidden" name="a" value="index">
    <div>
        <label for="q" class="etiqueta">Buscar</label>
        <input type="text" class="entrada" id="q" name="q" maxlength="100" value="<?= e($texto) ?>"
               placeholder="Fórmula o nombre">
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

<p class="texto-gris texto-chico"><?= count($compuestos) ?> registro(s).</p>

<div class="tabla-contenedor">
    <table class="tabla">
        <thead>
            <tr><th>Fórmula</th><th>Nombre común</th><th>Nombre tradicional</th><th>Nombre Stock</th><th>Tipo</th><th>Estado</th><th class="texto-derecha">Acciones</th></tr>
        </thead>
        <tbody>
            <?php if (count($compuestos) === 0): ?>
                <tr><td colspan="7" class="texto-centro texto-gris">No se encontraron compuestos.</td></tr>
            <?php endif; ?>
            <?php foreach ($compuestos as $c): ?>
                <tr>
                    <td class="negrita"><?= formula_html($c['formula']) ?></td>
                    <td><?= e($c['nombre_comun']) ?></td>
                    <td><?= e($c['nombre_tradicional']) ?></td>
                    <td><?= e($c['nombre_stock']) ?></td>
                    <td><?= e($c['tipo']) ?></td>
                    <td>
                        <?php if ($c['estado']): ?><span class="insignia">Activo</span>
                        <?php else: ?><span class="insignia insignia-gris">Inactivo</span><?php endif; ?>
                    </td>
                    <td class="texto-derecha sin-salto">
                        <a class="boton boton-azul boton-chico" href="<?= e(url('compuesto', 'ver', ['id' => $c['id_compuesto']])) ?>">Ver</a>
                        <a class="boton boton-chico" href="<?= e(url('compuesto', 'editar', ['id' => $c['id_compuesto']])) ?>">Editar</a>
                        <form method="post" class="en-linea"
                              action="<?= e(url('compuesto', $c['estado'] ? 'desactivar' : 'activar')) ?>"
                              data-confirmar="¿Confirma el cambio de estado de este compuesto?">
                            <?= campo_csrf() ?>
                            <input type="hidden" name="id" value="<?= (int) $c['id_compuesto'] ?>">
                            <button type="submit" class="boton boton-chico <?= $c['estado'] ? 'boton-rojo' : 'boton-gris' ?>">
                                <?= $c['estado'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
