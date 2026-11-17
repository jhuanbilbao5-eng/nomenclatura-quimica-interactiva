// Validaciones del lado del navegador (JavaScript puro).
// Las mismas reglas se vuelven a comprobar en PHP (core/Validator.php), porque la
// validación del navegador se puede omitir.
//
// Uso: los formularios con la clase "validar" se revisan al enviarse. Si hay errores,
// se agrega la clase "validado" para que el CSS pinte en rojo los campos inválidos y
// muestre su mensaje (.mensaje-error).
(function () {
    'use strict';

    // data-igual-a="#clave": el campo debe coincidir con otro (confirmar contraseña)
    function validarIgualdad(form) {
        form.querySelectorAll('[data-igual-a]').forEach(function (campo) {
            var otro = form.querySelector(campo.getAttribute('data-igual-a'));
            campo.setCustomValidity(otro && campo.value !== otro.value ? 'Las contraseñas no coinciden' : '');
        });
    }

    // data-opcion: las cuatro opciones de una pregunta no pueden repetirse
    function validarOpciones(form) {
        var campos = form.querySelectorAll('[data-opcion]');
        campos.forEach(function (campo) {
            var repetida = false;
            campos.forEach(function (otro) {
                if (otro !== campo && otro.value.trim() !== '' &&
                    otro.value.trim().toLowerCase() === campo.value.trim().toLowerCase()) {
                    repetida = true;
                }
            });
            campo.setCustomValidity(repetida ? 'Opción repetida' : '');
        });
    }

    // data-no-menor-que="#desde": la fecha final no puede ser menor que la inicial
    function validarRango(form) {
        form.querySelectorAll('[data-no-menor-que]').forEach(function (campo) {
            var otro = form.querySelector(campo.getAttribute('data-no-menor-que'));
            var invalido = otro && campo.value !== '' && otro.value !== '' && campo.value < otro.value;
            campo.setCustomValidity(invalido ? 'Rango de fechas inválido' : '');
        });
    }

    // Campos obligatorios: no se acepta un texto formado solo por espacios
    function validarNoVacios(form) {
        form.querySelectorAll('[required]').forEach(function (campo) {
            if ((campo.tagName === 'INPUT' && campo.type !== 'checkbox' && campo.type !== 'radio' && campo.type !== 'password')
                || campo.tagName === 'TEXTAREA') {
                if (campo.value !== '' && campo.value.trim() === '') {
                    campo.setCustomValidity('Campo vacío');
                } else if (campo.validationMessage === 'Campo vacío') {
                    campo.setCustomValidity('');
                }
            }
        });
    }

    function revisar(form) {
        validarNoVacios(form);
        validarIgualdad(form);
        validarOpciones(form);
        validarRango(form);
        return form.checkValidity();
    }

    document.querySelectorAll('form.validar').forEach(function (form) {
        form.setAttribute('novalidate', 'novalidate');   // usamos nuestros propios mensajes

        form.addEventListener('submit', function (evento) {
            if (!revisar(form)) {
                evento.preventDefault();
                evento.stopPropagation();
                var primero = form.querySelector(':invalid');
                if (primero) { primero.focus(); }
            }
            form.classList.add('validado');
        });

        // Una vez intentado el envío, se vuelve a revisar mientras el usuario escribe
        form.addEventListener('input', function () {
            if (form.classList.contains('validado')) { revisar(form); }
        });
    });
})();
