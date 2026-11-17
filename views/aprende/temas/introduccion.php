<?php /* Página ESTÁTICA del tema: Introducción (no consulta la base de datos). */ ?>
<nav class="migas"><a href="<?= e(url('aprende', 'index')) ?>">Aprende Química</a> › <?= e($tituloTema) ?></nav>

<div class="tema">
    <h1><?= e($tituloTema) ?></h1>

    <section>
        <h2>¿Qué es?</h2>
        <p>La <strong>nomenclatura química</strong> es el conjunto de reglas que se usan para <strong>nombrar</strong> y
            <strong>escribir la fórmula</strong> de los compuestos químicos. Gracias a ella, una persona en Bolivia y otra en
            cualquier parte del mundo entienden lo mismo al leer <strong>H<sub>2</sub>O</strong> o <strong>NaCl</strong>.
            Las reglas oficiales las propone la IUPAC (Unión Internacional de Química Pura y Aplicada).</p>
        <p>La <strong>valencia</strong> es la capacidad de combinación de un elemento: indica cuántos enlaces puede formar.
            En los compuestos, cada elemento actúa con un <strong>número de oxidación</strong> (positivo o negativo).
            El oxígeno casi siempre actúa con <strong>−2</strong> y el hidrógeno con <strong>+1</strong>
            (o −1 cuando se une a un metal, en los hidruros metálicos).</p>

        <h3>Valencias más usadas</h3>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Metales</th><th>Valencia</th><th>No metales</th><th>Valencia</th></tr></thead>
                <tbody>
                    <tr><td>Li, Na, K, Ag</td><td>1</td><td>H</td><td>1</td></tr>
                    <tr><td>Be, Mg, Ca, Ba, Zn</td><td>2</td><td>O</td><td>2</td></tr>
                    <tr><td>Al</td><td>3</td><td>F</td><td>1</td></tr>
                    <tr><td>Cu, Hg</td><td>1 y 2</td><td>Cl, Br, I</td><td>1, 3, 5 y 7</td></tr>
                    <tr><td>Au</td><td>1 y 3</td><td>S, Se, Te</td><td>2, 4 y 6</td></tr>
                    <tr><td>Fe, Co, Ni</td><td>2 y 3</td><td>N, P, As</td><td>3 y 5</td></tr>
                    <tr><td>Sn, Pb, Pt</td><td>2 y 4</td><td>C, Si</td><td>(2) y 4</td></tr>
                    <tr><td>Mn (en sales)</td><td>2, 3, 4, 6 y 7</td><td>B</td><td>3</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>¿Cómo se formula?</h2>
        <p>Para escribir la fórmula de un compuesto binario se usa la regla del <strong>intercambio de valencias</strong>:</p>
        <ol>
            <li>Se escribe primero el elemento <strong>menos electronegativo</strong> (normalmente el metal) y después el más electronegativo.</li>
            <li>La valencia de cada elemento se coloca como <strong>subíndice del otro</strong> (se “cruzan”).</li>
            <li>Si los dos subíndices se pueden dividir entre un mismo número, <strong>se simplifican</strong>.</li>
            <li>El subíndice 1 no se escribe.</li>
        </ol>
        <p class="ecuacion">Fe (valencia 3) + O (valencia 2) → Fe<sub>2</sub>O<sub>3</sub></p>
        <p class="ecuacion">Ca (valencia 2) + O (valencia 2) → Ca<sub>2</sub>O<sub>2</sub> → simplificado: CaO</p>
        <p>La suma de las cargas positivas y negativas siempre debe dar <strong>cero</strong>: en Fe<sub>2</sub>O<sub>3</sub>,
            2 × (+3) + 3 × (−2) = 0.</p>
    </section>

    <section>
        <h2>¿Cómo se nombra?</h2>
        <p>Se usan tres sistemas de nomenclatura:</p>
        <ul>
            <li><strong>Tradicional:</strong> usa prefijos y sufijos según la valencia con la que actúa el elemento.</li>
            <li><strong>Stock:</strong> indica la valencia con números romanos entre paréntesis, solo si el elemento tiene más de una.
                Ejemplo: óxido de hierro (III).</li>
            <li><strong>Sistemática (IUPAC):</strong> indica con prefijos griegos cuántos átomos hay de cada elemento.
                Ejemplo: trióxido de dihierro.</li>
        </ul>

        <h3>Sufijos y prefijos de la nomenclatura tradicional</h3>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>N.º de valencias del elemento</th><th>Valencia usada</th><th>Prefijo y sufijo</th><th>Ejemplo</th></tr></thead>
                <tbody>
                    <tr><td>1</td><td>única</td><td>…ico (o “de” + elemento)</td><td>óxido sódico / óxido de sodio</td></tr>
                    <tr><td rowspan="2">2</td><td>menor</td><td>…oso</td><td>óxido ferroso (Fe con 2)</td></tr>
                    <tr><td>mayor</td><td>…ico</td><td>óxido férrico (Fe con 3)</td></tr>
                    <tr><td rowspan="4">4</td><td>la menor</td><td>hipo…oso</td><td>anhídrido hipocloroso (Cl con 1)</td></tr>
                    <tr><td>la segunda</td><td>…oso</td><td>anhídrido cloroso (Cl con 3)</td></tr>
                    <tr><td>la tercera</td><td>…ico</td><td>anhídrido clórico (Cl con 5)</td></tr>
                    <tr><td>la mayor</td><td>per…ico</td><td>anhídrido perclórico (Cl con 7)</td></tr>
                </tbody>
            </table>
        </div>

        <h3>Prefijos de la nomenclatura sistemática</h3>
        <p>1 = mono · 2 = di · 3 = tri · 4 = tetra · 5 = penta · 6 = hexa · 7 = hepta</p>
    </section>

    <section>
        <h2>¿Cómo se forman los compuestos por reacción?</h2>
        <p>Los compuestos inorgánicos se relacionan entre sí como una “familia”. Este esquema resume los temas que verás:</p>
        <p class="ecuacion">Metal + Oxígeno → Óxido básico &nbsp;&nbsp;→ + Agua → Hidróxido (base)</p>
        <p class="ecuacion">No metal + Oxígeno → Óxido ácido (anhídrido) &nbsp;&nbsp;→ + Agua → Ácido oxácido</p>
        <p class="ecuacion">Elemento + Hidrógeno → Hidruro (o ácido hidrácido)</p>
        <p class="ecuacion">Ácido + Hidróxido → Sal + Agua &nbsp;&nbsp;(reacción de neutralización)</p>
    </section>

    <section>
        <h2>Ejemplos</h2>
        <div class="tabla-contenedor">
            <table class="tabla tabla-tema">
                <thead><tr><th>Fórmula</th><th>Tradicional</th><th>Stock</th><th>Sistemática</th></tr></thead>
                <tbody>
                    <tr><td>FeO</td><td>Óxido ferroso</td><td>Óxido de hierro (II)</td><td>Monóxido de hierro</td></tr>
                    <tr><td>Fe<sub>2</sub>O<sub>3</sub></td><td>Óxido férrico</td><td>Óxido de hierro (III)</td><td>Trióxido de dihierro</td></tr>
                    <tr><td>CaO</td><td>Óxido cálcico</td><td>Óxido de calcio</td><td>Monóxido de calcio</td></tr>
                    <tr><td>CO<sub>2</sub></td><td>Anhídrido carbónico</td><td>Óxido de carbono (IV)</td><td>Dióxido de carbono</td></tr>
                </tbody>
            </table>
        </div>
        <p class="nota">Consejo: aprende primero las valencias de la tabla; con ellas podrás formular casi cualquier compuesto.</p>
    </section>
</div>

<?php require BASE_PATH . '/views/aprende/navegacion.php'; ?>
