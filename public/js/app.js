// Comportamiento general de la interfaz (JavaScript puro, sin librerías ni frameworks).
(function () {
    'use strict';

    // 1. Menú desplegable en celular: el botón ☰ muestra u oculta el menú
    var botonMenu = document.getElementById('botonMenu');
    var menu = document.getElementById('menuPrincipal');
    if (botonMenu && menu) {
        botonMenu.addEventListener('click', function () {
            var abierto = menu.classList.toggle('abierto');
            botonMenu.setAttribute('aria-expanded', abierto ? 'true' : 'false');
        });
    }

    // 2. Botón × para cerrar los mensajes (avisos)
    document.querySelectorAll('.aviso-cerrar').forEach(function (boton) {
        boton.addEventListener('click', function () {
            boton.parentElement.remove();
        });
    });

    // 3. Confirmación antes de desactivar o activar un registro (formularios con data-confirmar)
    document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
        form.addEventListener('submit', function (evento) {
            if (!window.confirm(form.getAttribute('data-confirmar'))) {
                evento.preventDefault();
            }
        });
    });
})();
