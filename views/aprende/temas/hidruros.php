<?php /* Página ESTÁTICA del tema: Hidruros (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>Los <strong>hidruros</strong> son compuestos <strong>binarios</strong> formados por un elemento y el <strong>hidrógeno</strong>. Se distinguen tres grupos:</p>
        <ul>
            <li><strong>Hidruros metálicos:</strong> metal + hidrógeno. El hidrógeno actúa con −1. Ejemplos: NaH, CaH<sub>2</sub>.</li>
            <li><strong>Hidruros no metálicos con nombre especial:</strong> N, P, As, C, Si, B + hidrógeno. Ejemplos: NH<sub>3</sub> (amoníaco), CH<sub>4</sub> (metano).</li>
            <li><strong>Hidrácidos:</strong> hidrógeno + F, Cl, Br, I, S, Se o Te. Disueltos en agua son ácidos (se estudian en el tema Ácidos). Ejemplos: HCl, H<sub>2</sub>S.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <ul>
            <li><strong>Hidruros metálicos:</strong> se escribe el metal y luego H con la valencia del metal como subíndice: <strong>MH<sub>v</sub></strong>.</li>
            <li><strong>Hidruros no metálicos:</strong> se escribe el no metal y luego H<sub>v</sub>: NH<sub>3</sub>, PH<sub>3</sub>, CH<sub>4</sub>.</li>
            <li><strong>Hidrácidos:</strong> el hidrógeno se escribe primero: <strong>H<sub>v</sub>X</strong> (HCl, H<sub>2</sub>S).</li>
        </ul>
        <p class="ecuacion">Na (1) → NaH &nbsp;|&nbsp; Ca (2) → CaH<sub>2</sub> &nbsp;|&nbsp; Fe (3) → FeH<sub>3</sub> &nbsp;|&nbsp; N (3) → NH<sub>3</sub></p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <h3>Hidruros metálicos</h3>
        <ul>
            <li><strong>Tradicional:</strong> “hidruro” + metal con …oso o …ico: hidruro ferroso (FeH<sub>2</sub>), hidruro férrico (FeH<sub>3</sub>).</li>
            <li><strong>Stock:</strong> hidruro de hierro (III).</li>
            <li><strong>Sistemática:</strong> trihidruro de hierro.</li>
        </ul>
        <h3>Hidruros no metálicos</h3>
        <p>Se conocen por sus <strong>nombres especiales</strong>: NH<sub>3</sub> amoníaco, PH<sub>3</sub> fosfina, AsH<sub>3</sub> arsina,
            CH<sub>4</sub> metano, SiH<sub>4</sub> silano, BH<sub>3</sub> borano. En sistemática: trihidruro de nitrógeno, tetrahidruro de carbono.</p>
        <h3>Hidrácidos (estado gaseoso)</h3>
        <p>Se nombran con el no metal terminado en <strong>…uro</strong> + “de hidrógeno”: HCl cloruro de hidrógeno, H<sub>2</sub>S sulfuro de hidrógeno.</p>
    </section>

    <section>
        <h2>¿Cómo se forma por reacción?</h2>
        <p>El elemento reacciona directamente con hidrógeno gaseoso (H<sub>2</sub>):</p>
        <p class="ecuacion">2 Na + H<sub>2</sub> → 2 NaH</p>
        <p class="ecuacion">Ca + H<sub>2</sub> → CaH<sub>2</sub></p>
        <p class="ecuacion">N<sub>2</sub> + 3 H<sub>2</sub> → 2 NH<sub>3</sub> &nbsp;&nbsp;(síntesis del amoníaco)</p>
        <p class="ecuacion">H<sub>2</sub> + Cl<sub>2</sub> → 2 HCl</p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Tradicional</th><th>Stock</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>NaH</td><td>Hidruro sódico</td><td>Hidruro de sodio</td><td>Monohidruro de sodio</td></tr>
                    <tr><td>CaH<sub>2</sub></td><td>Hidruro cálcico</td><td>Hidruro de calcio</td><td>Dihidruro de calcio</td></tr>
                    <tr><td>FeH<sub>2</sub></td><td>Hidruro ferroso</td><td>Hidruro de hierro (II)</td><td>Dihidruro de hierro</td></tr>
                    <tr><td>FeH<sub>3</sub></td><td>Hidruro férrico</td><td>Hidruro de hierro (III)</td><td>Trihidruro de hierro</td></tr>
                    <tr><td>NH<sub>3</sub></td><td>Amoníaco</td><td>Hidruro de nitrógeno (III)</td><td>Trihidruro de nitrógeno</td></tr>
                    <tr><td>CH<sub>4</sub></td><td>Metano</td><td>Hidruro de carbono (IV)</td><td>Tetrahidruro de carbono</td></tr>
                </tbody>
            </table>
        </div>
        <p class="nota">El metano (CH<sub>4</sub>) es el componente principal del gas natural que llega a los hogares de Sacaba por red de gas domiciliario.</p>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
