<?php /* Página ESTÁTICA del tema: Ácidos (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>Los <strong>ácidos</strong> son compuestos que, disueltos en agua, liberan iones hidrógeno (H<sup>+</sup>).
            Tienen sabor agrio y cambian el papel tornasol azul a <strong>rojo</strong>. Hay dos clases:</p>
        <ul>
            <li><strong>Hidrácidos (binarios):</strong> hidrógeno + no metal (F, Cl, Br, I, S, Se, Te). No tienen oxígeno. Ejemplo: HCl.</li>
            <li><strong>Oxácidos (ternarios):</strong> hidrógeno + no metal + oxígeno. Ejemplo: H<sub>2</sub>SO<sub>4</sub>.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <h3>Hidrácidos</h3>
        <p>Se escribe H con la valencia del no metal como subíndice y luego el no metal: <strong>H<sub>v</sub>X</strong>.
            F, Cl, Br, I actúan con 1 (HF, HCl, HBr, HI) y S, Se, Te con 2 (H<sub>2</sub>S).</p>
        <h3>Oxácidos (regla práctica)</h3>
        <ul>
            <li>Si la valencia del no metal es <strong>impar</strong>: H X O<sub>(v+1)/2</sub> &nbsp;→ Cl (7): HClO<sub>4</sub>; N (5): HNO<sub>3</sub>.</li>
            <li>Si la valencia es <strong>par</strong>: H<sub>2</sub> X O<sub>(v+2)/2</sub> &nbsp;→ S (6): H<sub>2</sub>SO<sub>4</sub>; C (4): H<sub>2</sub>CO<sub>3</sub>.</li>
        </ul>
        <p class="nota">Caso especial: el fósforo forma el ácido fosfórico H<sub>3</sub>PO<sub>4</sub> (anhídrido fosfórico + 3 moléculas de agua).</p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <h3>Hidrácidos</h3>
        <ul>
            <li>En agua (tradicional): “ácido” + no metal terminado en <strong>…hídrico</strong>: ácido clorhídrico, ácido sulfhídrico.</li>
            <li>En estado gaseoso (sistemática): no metal terminado en <strong>…uro</strong> + “de hidrógeno”: cloruro de hidrógeno.</li>
        </ul>
        <h3>Oxácidos</h3>
        <ul>
            <li><strong>Tradicional:</strong> se cambia la palabra “anhídrido” por “ácido”: anhídrido sulfúrico → ácido sulfúrico.</li>
            <li><strong>Stock (funcional):</strong> “ácido” + prefijo de oxígenos + oxo + no metal …ico + valencia: ácido tetraoxosulfúrico (VI).</li>
            <li><strong>Sistemática:</strong> prefijo + oxo + no metal …ato + valencia + “de hidrógeno”: tetraoxosulfato (VI) de hidrógeno.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se forma por reacción?</h2>
        <p>Los <strong>oxácidos</strong> se forman cuando un <strong>anhídrido</strong> (óxido ácido) reacciona con <strong>agua</strong>:</p>
        <p class="ecuacion">CO<sub>2</sub> + H<sub>2</sub>O → H<sub>2</sub>CO<sub>3</sub> &nbsp;&nbsp;(así se forma el gas de las gaseosas en el agua)</p>
        <p class="ecuacion">SO<sub>3</sub> + H<sub>2</sub>O → H<sub>2</sub>SO<sub>4</sub></p>
        <p class="ecuacion">N<sub>2</sub>O<sub>5</sub> + H<sub>2</sub>O → 2 HNO<sub>3</sub></p>
        <p class="ecuacion">Cl<sub>2</sub>O<sub>7</sub> + H<sub>2</sub>O → 2 HClO<sub>4</sub></p>
        <p class="ecuacion">P<sub>2</sub>O<sub>5</sub> + 3 H<sub>2</sub>O → 2 H<sub>3</sub>PO<sub>4</sub></p>
        <p>Los <strong>hidrácidos</strong> se forman al unir hidrógeno con el no metal y disolver el gas en agua:</p>
        <p class="ecuacion">H<sub>2</sub> + Cl<sub>2</sub> → 2 HCl</p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Tradicional</th><th>Stock (funcional)</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>HCl</td><td>Ácido clorhídrico</td><td>—</td><td>Cloruro de hidrógeno</td></tr>
                    <tr><td>H<sub>2</sub>S</td><td>Ácido sulfhídrico</td><td>—</td><td>Sulfuro de dihidrógeno</td></tr>
                    <tr><td>H<sub>2</sub>CO<sub>3</sub></td><td>Ácido carbónico</td><td>Ácido trioxocarbónico (IV)</td><td>Trioxocarbonato (IV) de hidrógeno</td></tr>
                    <tr><td>H<sub>2</sub>SO<sub>3</sub></td><td>Ácido sulfuroso</td><td>Ácido trioxosulfúrico (IV)</td><td>Trioxosulfato (IV) de hidrógeno</td></tr>
                    <tr><td>H<sub>2</sub>SO<sub>4</sub></td><td>Ácido sulfúrico</td><td>Ácido tetraoxosulfúrico (VI)</td><td>Tetraoxosulfato (VI) de hidrógeno</td></tr>
                    <tr><td>HNO<sub>3</sub></td><td>Ácido nítrico</td><td>Ácido trioxonítrico (V)</td><td>Trioxonitrato (V) de hidrógeno</td></tr>
                    <tr><td>HClO</td><td>Ácido hipocloroso</td><td>Ácido oxoclórico (I)</td><td>Oxoclorato (I) de hidrógeno</td></tr>
                    <tr><td>HClO<sub>4</sub></td><td>Ácido perclórico</td><td>Ácido tetraoxoclórico (VII)</td><td>Tetraoxoclorato (VII) de hidrógeno</td></tr>
                </tbody>
            </table>
        </div>
        <p class="nota">¡Cuidado! Los ácidos fuertes como el sulfúrico (de las baterías) o el muriático (HCl) queman la piel. Se manipulan con guantes y lentes.</p>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
