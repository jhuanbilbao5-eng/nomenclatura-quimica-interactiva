<div class="tarjeta tarjeta-formulario">
    <h1 class="texto-centro">Mi cuenta</h1>
    <p class="texto-centro texto-gris texto-chico">
        <?= e($usuario['nombres'] . ' ' . $usuario['apellidos']) ?> · <?= e($usuario['correo']) ?>
        · <?= e(Auth::usuario()['rol']) ?>
    </p>

    <?php foreach ($errores as $error): ?>
        <div class="aviso aviso-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <h2>Cambiar contraseña</h2>
    <form method="post" action="<?= e(url('auth', 'cuenta')) ?>" class="validar">
        <?= campo_csrf() ?>
        <div class="campo">
            <label for="actual" class="etiqueta">Contraseña actual</label>
            <input type="password" class="entrada" id="actual" name="actual" required autocomplete="current-password">
            <div class="mensaje-error">Ingrese su contraseña actual.</div>
        </div>
        <div class="campo">
            <label for="nueva" class="etiqueta">Nueva contraseña (mínimo 8 caracteres, con letras y números)</label>
            <input type="password" class="entrada" id="nueva" name="nueva" required minlength="8" maxlength="72"
                   pattern="(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])(?=.*\d).{8,}" autocomplete="new-password">
            <div class="mensaje-error">Mínimo 8 caracteres, con letras y números.</div>
        </div>
        <div class="campo">
            <label for="confirmar" class="etiqueta">Confirmar nueva contraseña</label>
            <input type="password" class="entrada" id="confirmar" name="confirmar" required data-igual-a="#nueva"
                   autocomplete="new-password">
            <div class="mensaje-error">Las contraseñas no coinciden.</div>
        </div>
        <button type="submit" class="boton boton-bloque">Guardar nueva contraseña</button>
    </form>
</div>
