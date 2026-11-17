<?php /* Página ESTÁTICA del tema: Hidróxidos (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>Los <strong>hidróxidos</strong> o <strong>bases</strong> son compuestos <strong>ternarios</strong> formados por un metal y el
            grupo <strong>hidroxilo (OH)</strong>, que actúa con carga −1.</p>
        <p>Tienen sabor amargo, son jabonosos al tacto y cambian el papel tornasol rojo a <strong>azul</strong>.
            Ejemplos de la vida diaria: la soda cáustica (NaOH) y la cal apagada (Ca(OH)<sub>2</sub>).</p>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <p>Fórmula general: <strong>M(OH)<sub>v</sub></strong>, donde M es el metal y v su valencia.</p>
        <ol>
            <li>Se escribe el símbolo del metal.</li>
            <li>Se escribe el grupo OH tantas veces como la valencia del metal.</li>
            <li>Si la valencia es 1, no se usan paréntesis: NaOH. Si es mayor, el OH va entre paréntesis: Ca(OH)<sub>2</sub>.</li>
        </ol>
        <p class="ecuacion">K (1) → KOH &nbsp;|&nbsp; Mg (2) → Mg(OH)<sub>2</sub> &nbsp;|&nbsp; Al (3) → Al(OH)<sub>3</sub> &nbsp;|&nbsp; Fe (3) → Fe(OH)<sub>3</sub></p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <ul>
            <li><strong>Tradicional:</strong> “hidróxido” + metal con …oso o …ico: hidróxido ferroso, hidróxido férrico. Con una sola valencia: hidróxido de sodio o hidróxido sódico.</li>
            <li><strong>Stock:</strong> “hidróxido de” + metal + valencia en romanos: hidróxido de hierro (II).</li>
            <li><strong>Sistemática:</strong> prefijo de cantidad + “hidróxido de” + metal: dihidróxido de hierro.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se forma por reacción?</h2>
        <p>Un <strong>óxido básico</strong> reacciona con <strong>agua</strong>:</p>
        <p class="ecuacion">CaO + H<sub>2</sub>O → Ca(OH)<sub>2</sub> &nbsp;&nbsp;(cal viva + agua → cal apagada)</p>
        <p class="ecuacion">Na<sub>2</sub>O + H<sub>2</sub>O → 2 NaOH</p>
        <p class="ecuacion">Fe<sub>2</sub>O<sub>3</sub> + 3 H<sub>2</sub>O → 2 Fe(OH)<sub>3</sub></p>
        <p>También los metales muy activos reaccionan directamente con el agua y liberan hidrógeno:</p>
        <p class="ecuacion">2 Na + 2 H<sub>2</sub>O → 2 NaOH + H<sub>2</sub></p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Tradicional</th><th>Stock</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>NaOH</td><td>Hidróxido sódico</td><td>Hidróxido de sodio</td><td>Monohidróxido de sodio</td></tr>
                    <tr><td>KOH</td><td>Hidróxido potásico</td><td>Hidróxido de potasio</td><td>Monohidróxido de potasio</td></tr>
                    <tr><td>Ca(OH)<sub>2</sub></td><td>Hidróxido cálcico</td><td>Hidróxido de calcio</td><td>Dihidróxido de calcio</td></tr>
                    <tr><td>CuOH</td><td>Hidróxido cuproso</td><td>Hidróxido de cobre (I)</td><td>Monohidróxido de cobre</td></tr>
                    <tr><td>Cu(OH)<sub>2</sub></td><td>Hidróxido cúprico</td><td>Hidróxido de cobre (II)</td><td>Dihidróxido de cobre</td></tr>
                    <tr><td>Fe(OH)<sub>2</sub></td><td>Hidróxido ferroso</td><td>Hidróxido de hierro (II)</td><td>Dihidróxido de hierro</td></tr>
                    <tr><td>Fe(OH)<sub>3</sub></td><td>Hidróxido férrico</td><td>Hidróxido de hierro (III)</td><td>Trihidróxido de hierro</td></tr>
                    <tr><td>Al(OH)<sub>3</sub></td><td>Hidróxido alumínico</td><td>Hidróxido de aluminio</td><td>Trihidróxido de aluminio</td></tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
