<?php /* Página ESTÁTICA del tema: Óxidos (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>Los <strong>óxidos</strong> son compuestos <strong>binarios</strong> formados por un elemento y el
            <strong>oxígeno</strong>, que actúa con número de oxidación −2. Hay dos clases:</p>
        <ul>
            <li><strong>Óxidos básicos (metálicos):</strong> metal + oxígeno. Ejemplos: CaO (cal viva), Fe<sub>2</sub>O<sub>3</sub> (herrumbre).</li>
            <li><strong>Óxidos ácidos o anhídridos (no metálicos):</strong> no metal + oxígeno. Ejemplos: CO<sub>2</sub>, SO<sub>3</sub>.</li>
        </ul>
        <p>Se llaman “básicos” porque con agua forman hidróxidos (bases), y “ácidos” porque con agua forman ácidos.</p>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <p>Fórmula general: <strong>X<sub>2</sub>O<sub>v</sub></strong>, donde X es el elemento y v su valencia.</p>
        <ol>
            <li>Se escribe el elemento y luego el oxígeno.</li>
            <li>Se intercambian las valencias: la del elemento pasa al oxígeno y la del oxígeno (2) pasa al elemento.</li>
            <li>Se simplifica si es posible.</li>
        </ol>
        <p class="ecuacion">Na (1) → Na<sub>2</sub>O &nbsp;|&nbsp; Ca (2) → Ca<sub>2</sub>O<sub>2</sub> = CaO &nbsp;|&nbsp; Al (3) → Al<sub>2</sub>O<sub>3</sub></p>
        <p class="ecuacion">C (4) → C<sub>2</sub>O<sub>4</sub> = CO<sub>2</sub> &nbsp;|&nbsp; N (5) → N<sub>2</sub>O<sub>5</sub> &nbsp;|&nbsp; S (6) → S<sub>2</sub>O<sub>6</sub> = SO<sub>3</sub> &nbsp;|&nbsp; Cl (7) → Cl<sub>2</sub>O<sub>7</sub></p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <h3>Óxidos básicos</h3>
        <ul>
            <li><strong>Tradicional:</strong> “óxido” + nombre del metal con …oso (valencia menor) o …ico (valencia mayor). Si el metal tiene una sola valencia: óxido de sodio u óxido sódico.</li>
            <li><strong>Stock:</strong> “óxido de” + metal + valencia en números romanos: óxido de hierro (III).</li>
            <li><strong>Sistemática:</strong> prefijo + “óxido de” + prefijo + metal: trióxido de dihierro.</li>
        </ul>
        <h3>Óxidos ácidos (anhídridos)</h3>
        <ul>
            <li><strong>Tradicional:</strong> “anhídrido” + no metal con hipo…oso, …oso, …ico o per…ico, según la valencia.</li>
            <li><strong>Stock:</strong> óxido de cloro (VII).</li>
            <li><strong>Sistemática:</strong> heptóxido de dicloro.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se forma por reacción?</h2>
        <p>Un metal o un no metal reacciona con el oxígeno del aire (oxidación o combustión):</p>
        <p class="ecuacion">2 Ca + O<sub>2</sub> → 2 CaO</p>
        <p class="ecuacion">4 Fe + 3 O<sub>2</sub> → 2 Fe<sub>2</sub>O<sub>3</sub> &nbsp;&nbsp;(así se oxida el hierro)</p>
        <p class="ecuacion">C + O<sub>2</sub> → CO<sub>2</sub> &nbsp;&nbsp;(combustión del carbón)</p>
        <p class="ecuacion">2 Cl<sub>2</sub> + 7 O<sub>2</sub> → 2 Cl<sub>2</sub>O<sub>7</sub></p>
        <p class="nota">Recuerda balancear la ecuación: debe haber el mismo número de átomos de cada elemento a ambos lados.</p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Tradicional</th><th>Stock</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>Na<sub>2</sub>O</td><td>Óxido sódico</td><td>Óxido de sodio</td><td>Monóxido de disodio</td></tr>
                    <tr><td>CaO</td><td>Óxido cálcico</td><td>Óxido de calcio</td><td>Monóxido de calcio</td></tr>
                    <tr><td>Cu<sub>2</sub>O</td><td>Óxido cuproso</td><td>Óxido de cobre (I)</td><td>Monóxido de dicobre</td></tr>
                    <tr><td>CuO</td><td>Óxido cúprico</td><td>Óxido de cobre (II)</td><td>Monóxido de cobre</td></tr>
                    <tr><td>Fe<sub>2</sub>O<sub>3</sub></td><td>Óxido férrico</td><td>Óxido de hierro (III)</td><td>Trióxido de dihierro</td></tr>
                    <tr><td>CO<sub>2</sub></td><td>Anhídrido carbónico</td><td>Óxido de carbono (IV)</td><td>Dióxido de carbono</td></tr>
                    <tr><td>SO<sub>3</sub></td><td>Anhídrido sulfúrico</td><td>Óxido de azufre (VI)</td><td>Trióxido de azufre</td></tr>
                    <tr><td>Cl<sub>2</sub>O</td><td>Anhídrido hipocloroso</td><td>Óxido de cloro (I)</td><td>Monóxido de dicloro</td></tr>
                    <tr><td>Cl<sub>2</sub>O<sub>7</sub></td><td>Anhídrido perclórico</td><td>Óxido de cloro (VII)</td><td>Heptóxido de dicloro</td></tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
