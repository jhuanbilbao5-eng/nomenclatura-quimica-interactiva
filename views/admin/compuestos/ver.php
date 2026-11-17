<div class="encabezado">
    <h1>Consultar compuesto</h1>
    <div class="grupo-botones">
        <a class="boton" href="<?= e(url('compuesto', 'editar', ['id' => $compuesto['id_compuesto']])) ?>">Editar</a>
        <a class="boton boton-gris" href="<?= e(url('compuesto', 'index')) ?>">Volver al listado</a>
    </div>
</div>

<div class="tarjeta tarjeta-verde">
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
        <dt>Nombre común</dt>
        <dd><?= $compuesto['nombre_comun'] ? e($compuesto['nombre_comun']) : '<span class="texto-gris">—</span>' ?></dd>
        <dt>Para qué sirve</dt>
        <dd><?= $compuesto['uso'] ? e($compuesto['uso']) : '<span class="texto-gris">—</span>' ?></dd>
        <dt>En la pantalla principal</dt>
        <dd><?= $compuesto['cotidiano'] ? 'Sí (compuesto de la vida cotidiana)' : 'No' ?></dd>
        <dt>Estado</dt>
        <dd><?= $compuesto['estado'] ? '<span class="insignia">Activo</span>' : '<span class="insignia insignia-gris">Inactivo</span>' ?></dd>
    </dl>
</div>
