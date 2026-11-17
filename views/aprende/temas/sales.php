<?php /* Página ESTÁTICA del tema: Sales (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>Las <strong>sales</strong> son compuestos que resultan de <strong>reemplazar los hidrógenos de un ácido por un metal</strong>.
            Se obtienen cuando un ácido reacciona con un hidróxido (neutralización). Según cuántos hidrógenos u OH quedan, son:</p>
        <ul>
            <li><strong>Sales neutras:</strong> se reemplazan todos los hidrógenos del ácido. Pueden ser <em>haloideas</em> (de hidrácidos, sin oxígeno: NaCl) u <em>oxisales</em> (de oxácidos: CaCO<sub>3</sub>).</li>
            <li><strong>Sales ácidas:</strong> conservan uno o más hidrógenos del ácido: NaHCO<sub>3</sub>.</li>
            <li><strong>Sales básicas:</strong> conservan uno o más grupos OH del hidróxido: Mg(OH)Cl.</li>
            <li><strong>Sales mixtas (dobles):</strong> tienen dos metales distintos (o dos aniones): KNaSO<sub>4</sub>.</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <ol>
            <li>Se quita el hidrógeno al ácido y se obtiene el <strong>anión</strong>, cuya carga negativa es igual al número de H quitados:
                H<sub>2</sub>SO<sub>4</sub> → SO<sub>4</sub><sup>2−</sup>; HNO<sub>3</sub> → NO<sub>3</sub><sup>−</sup>; HCl → Cl<sup>−</sup>.</li>
            <li>Se escribe el metal y el anión y se <strong>intercambian</strong> la valencia del metal y la carga del anión.</li>
            <li>Si el anión tiene más de un átomo y su subíndice es mayor que 1, va entre paréntesis. Se simplifica si es posible.</li>
        </ol>
        <p class="ecuacion">Fe (3) + SO<sub>4</sub> (2) → Fe<sub>2</sub>(SO<sub>4</sub>)<sub>3</sub> &nbsp;|&nbsp; Ca (2) + NO<sub>3</sub> (1) → Ca(NO<sub>3</sub>)<sub>2</sub> &nbsp;|&nbsp; Ca (2) + CO<sub>3</sub> (2) → CaCO<sub>3</sub></p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <p>Primero se cambia la terminación del ácido (regla: “el <strong>oso</strong> toca el p<strong>ito</strong> y el p<strong>ico</strong> del p<strong>ato</strong>”):</p>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Terminación del ácido</th><th>Terminación de la sal</th><th>Ejemplo</th></tr></thead>
                <tbody>
                    <tr><td>…hídrico</td><td>…uro</td><td>ácido clorhídrico → clor<strong>uro</strong></td></tr>
                    <tr><td>hipo…oso</td><td>hipo…ito</td><td>ácido hipocloroso → hipoclor<strong>ito</strong></td></tr>
                    <tr><td>…oso</td><td>…ito</td><td>ácido sulfuroso → sulf<strong>ito</strong></td></tr>
                    <tr><td>…ico</td><td>…ato</td><td>ácido sulfúrico → sulf<strong>ato</strong></td></tr>
                    <tr><td>per…ico</td><td>per…ato</td><td>ácido perclórico → perclor<strong>ato</strong></td></tr>
                </tbody>
            </table>
        </div>
        <ul>
            <li><strong>Tradicional:</strong> nombre del anión + metal con …oso o …ico: sulfato férrico, cloruro sódico.</li>
            <li><strong>Stock:</strong> nombre del anión + “de” + metal + valencia: sulfato de hierro (III).</li>
            <li><strong>Sistemática:</strong> se indican los oxígenos y la valencia del no metal: tetraoxosulfato (VI) de hierro (III).</li>
            <li><strong>Sales ácidas:</strong> se agrega “ácido” o el prefijo “hidrógeno”: carbonato ácido de sodio = hidrogenocarbonato de sodio (bicarbonato).</li>
            <li><strong>Sales básicas:</strong> se agrega “básico” o el prefijo “hidroxi”: cloruro básico de magnesio = hidroxicloruro de magnesio.</li>
            <li><strong>Sales mixtas:</strong> se nombran los dos metales: sulfato de potasio y sodio (en Stock, en orden alfabético).</li>
        </ul>
    </section>

    <section>
        <h2>¿Cómo se forma por reacción?</h2>
        <p>Por <strong>neutralización</strong>: ácido + hidróxido → sal + agua.</p>
        <p class="ecuacion">HCl + NaOH → NaCl + H<sub>2</sub>O &nbsp;&nbsp;(sal neutra haloidea)</p>
        <p class="ecuacion">H<sub>2</sub>SO<sub>4</sub> + 2 NaOH → Na<sub>2</sub>SO<sub>4</sub> + 2 H<sub>2</sub>O &nbsp;&nbsp;(oxisal neutra)</p>
        <p class="ecuacion">2 HNO<sub>3</sub> + Ca(OH)<sub>2</sub> → Ca(NO<sub>3</sub>)<sub>2</sub> + 2 H<sub>2</sub>O</p>
        <p class="ecuacion">H<sub>2</sub>CO<sub>3</sub> + NaOH → NaHCO<sub>3</sub> + H<sub>2</sub>O &nbsp;&nbsp;(sal ácida: se reemplaza solo un H)</p>
        <p class="ecuacion">Mg(OH)<sub>2</sub> + HCl → Mg(OH)Cl + H<sub>2</sub>O &nbsp;&nbsp;(sal básica: queda un OH)</p>
        <p class="ecuacion">H<sub>2</sub>SO<sub>4</sub> + NaOH + KOH → KNaSO<sub>4</sub> + 2 H<sub>2</sub>O &nbsp;&nbsp;(sal mixta)</p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Clase</th><th>Tradicional</th><th>Stock</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>NaCl</td><td>Neutra</td><td>Cloruro sódico</td><td>Cloruro de sodio</td><td>Cloruro de sodio</td></tr>
                    <tr><td>FeCl<sub>3</sub></td><td>Neutra</td><td>Cloruro férrico</td><td>Cloruro de hierro (III)</td><td>Tricloruro de hierro</td></tr>
                    <tr><td>CaCO<sub>3</sub></td><td>Neutra</td><td>Carbonato cálcico</td><td>Carbonato de calcio</td><td>Trioxocarbonato (IV) de calcio</td></tr>
                    <tr><td>NaClO</td><td>Neutra</td><td>Hipoclorito sódico</td><td>Hipoclorito de sodio</td><td>Oxoclorato (I) de sodio</td></tr>
                    <tr><td>CuSO<sub>4</sub></td><td>Neutra</td><td>Sulfato cúprico</td><td>Sulfato de cobre (II)</td><td>Tetraoxosulfato (VI) de cobre (II)</td></tr>
                    <tr><td>NaHCO<sub>3</sub></td><td>Ácida</td><td>Carbonato ácido de sodio</td><td>Hidrogenocarbonato de sodio</td><td>Hidrogenotrioxocarbonato (IV) de sodio</td></tr>
                    <tr><td>Mg(OH)Cl</td><td>Básica</td><td>Cloruro básico de magnesio</td><td>Hidroxicloruro de magnesio</td><td>Hidroxicloruro de magnesio</td></tr>
                    <tr><td>KNaSO<sub>4</sub></td><td>Mixta</td><td>Sulfato sódico potásico</td><td>Sulfato de potasio y sodio</td><td>Tetraoxosulfato (VI) de potasio y sodio</td></tr>
                </tbody>
            </table>
        </div>
        <p class="nota">En casa usamos muchas sales: la sal de mesa (NaCl), el bicarbonato (NaHCO<sub>3</sub>), la lavandina (NaClO) y el yeso (CaSO<sub>4</sub>).</p>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
