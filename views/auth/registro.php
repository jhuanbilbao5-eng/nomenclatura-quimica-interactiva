<div class="tarjeta tarjeta-formulario-ancha">
    <h1 class="texto-centro">Crear cuenta de estudiante</h1>

    <?php foreach ($errores as $error): ?>
        <div class="aviso aviso-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('auth', 'registro')) ?>" class="validar">
        <?= campo_csrf() ?>
        <div class="rejilla rejilla-2">
            <div class="campo">
                <label for="nombres" class="etiqueta">Nombres</label>
                <input type="text" class="entrada" id="nombres" name="nombres" required maxlength="60"
                       pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s]+" value="<?= e($datos['nombres']) ?>">
                <div class="mensaje-error">Obligatorio. Solo letras.</div>
            </div>
            <div class="campo">
                <label for="apellidos" class="etiqueta">Apellidos</label>
                <input type="text" class="entrada" id="apellidos" name="apellidos" required maxlength="60"
                       pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s]+" value="<?= e($datos['apellidos']) ?>">
                <div class="mensaje-error">Obligatorio. Solo letras.</div>
            </div>
        </div>
        <div class="campo">
            <label for="correo" class="etiqueta">Correo electrónico</label>
            <input type="email" class="entrada" id="correo" name="correo" required maxlength="100"
                   autocomplete="username" value="<?= e($datos['correo']) ?>">
            <div class="mensaje-error">Ingrese un correo válido.</div>
        </div>
        <div class="campo">
            <label for="clave" class="etiqueta">Contraseña (mínimo 8 caracteres, con letras y números)</label>
            <input type="password" class="entrada" id="clave" name="clave" required minlength="8" maxlength="72"
                   pattern="(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])(?=.*\d).{8,}" autocomplete="new-password">
            <div class="mensaje-error">Mínimo 8 caracteres, con letras y números.</div>
        </div>
        <div class="campo">
            <label for="confirmar" class="etiqueta">Confirmar contraseña</label>
            <input type="password" class="entrada" id="confirmar" name="confirmar" required data-igual-a="#clave"
                   autocomplete="new-password">
            <div class="mensaje-error">Las contraseñas no coinciden.</div>
        </div>
        <button type="submit" class="boton boton-bloque">Registrarme</button>
    </form>

    <p class="texto-centro texto-chico" style="margin-top: 1rem; margin-bottom: 0;">
        ¿Ya tienes cuenta? <a href="<?= e(url('auth', 'login')) ?>">Inicia sesión</a>
    </p>
</div>
