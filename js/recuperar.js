/*
 * Script de validación para el formulario de recuperar contraseña
 */
const email = document.getElementById('email');
const emailError = document.getElementById('errorEmail');

(function () {

    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if(event.submitter.name=='volver'){
                    return;
                }
                event.preventDefault();
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    emailMsgError();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


function emailMsgError() {
    if (email.validity.valueMissing) {
        emailError.textContent = 'Debe introducir una dirección de correo electrónico.';
    } else if (email.validity.typeMismatch) {
        emailError.textContent = 'El valor introducido debe ser una dirección de correo electrónico.';
    } else if (email.validitytooLong) {
        nombreError.textContent = 'Mira que hay tener paciencia para escribir tanto';
    }
}

