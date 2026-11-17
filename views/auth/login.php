<div class="tarjeta tarjeta-formulario">
    <h1 class="texto-centro">Iniciar sesión</h1>
    <p class="texto-centro texto-gris texto-chico">Ingrese con su correo electrónico y su contraseña.</p>

    <?php foreach ($errores as $error): ?>
        <div class="aviso aviso-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('auth', 'login')) ?>" class="validar">
        <?= campo_csrf() ?>
        <div class="campo">
            <label for="correo" class="etiqueta">Correo electrónico</label>
            <input type="email" class="entrada" id="correo" name="correo" required maxlength="100"
                   autocomplete="username" placeholder="ejemplo@correo.com" value="<?= e($correo) ?>">
            <div class="mensaje-error">Ingrese un correo válido.</div>
        </div>
        <div class="campo">
            <label for="clave" class="etiqueta">Contraseña</label>
            <input type="password" class="entrada" id="clave" name="clave" required autocomplete="current-password">
            <div class="mensaje-error">Ingrese su contraseña.</div>
        </div>
        <button type="submit" class="boton boton-bloque">Ingresar</button>
    </form>

    <p class="texto-centro texto-chico" style="margin-top: 1rem; margin-bottom: 0;">
        ¿No tienes cuenta? <a href="<?= e(url('auth', 'registro')) ?>">Regístrate aquí</a>
    </p>
</div>
