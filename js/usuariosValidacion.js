const dni = document.getElementById('registroDni');
const dniError = document.getElementById('errorDni');

const paswword = document.getElementById('registroPass');
const paswwordError = document.getElementById('errorPassword');

const nombre = document.getElementById('registroNombre');
const nombreError = document.getElementById('errorNombre');

const apellidos = document.getElementById('registroApellidos');
const apellidosError = document.getElementById('errorApellidos');

const fechaNac = document.getElementById('registroFechaNac');
const fechaNacError = document.getElementById('errorFechaNac');

const email = document.getElementById('registroEmail');
const emailError = document.getElementById('errorEmail');

(function () {

    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    dniMsgError();
                    passwordMsgError();
                    nombreMsgError();
                    apellidosMsgError();
                    fechaNacMsgError();
                    emailMsgError();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

//Funciones que muestran el mensaje de error
function dniMsgError() {
    
    if (dni.validity.valueMissing) {        
        dniError.textContent = 'Debe introducir un DNI';
    } else if (dni.validity.patternMismatch) {            
        dniError.textContent = 'El DNI solo puede contener numeros y una única letra';
    } else if (dni.validity.tooShort) {
        dniError.textContent = 'El dni debe tener ${ dni.minLength } caracteres; ha introducido ${ dni.value.length }.';
    }
    
}

function passwordMsgError() {
    if (paswword.validity.valueMissing) {
        paswwordError.textContent = 'Debe introducir una contraseña';
    } else if (paswword.validity.patternMismatch) {            
        paswwordError.textContent = 'La contraseña debe incluir minimo una mayuscula, una minuscula y un número';
    } else if (paswword.validity.tooShort) {
        paswwordError.textContent = 'La cotraseña debe tener al menos 8 caracteres';
    } else if (paswword.validitytooLong) {
        paswwordError.textContent = 'La contraseña es demasiado larga';
    }
}

function nombreMsgError() {
    if (nombre.validity.valueMissing) {
        nombreError.textContent = 'Debe introducir un nombre';
    } else if (nombre.validity.patternMismatch) {            
        nombreError.textContent = 'Ha introduciodo carácteres no válidos';
    } else if (nombre.validitytooLong) {
        nombreError.textContent = 'Mira que hay tener paciencia para escribir tanto';
    }
}

function apellidosMsgError() {
    if (apellidos.validity.valueMissing) {
        apellidosError.textContent = 'Debe introducir un apellido';
    } else if (apellidos.validity.patternMismatch) {            
        apellidosError.textContent = 'Ha introduciodo carácteres no válidos';
    } else if (apellidos.validitytooLong) {
        nombreError.textContent = 'Mira que hay tener paciencia para escribir tanto';
    }
}

function fechaNacMsgError() {
    if (fechaNac.validity.valueMissing) {
        fechaNacError.textContent = 'Debe introducir una fecha de nacimiento';
    }
}

function emailMsgError() {
    if (email.validity.valueMissing) {
        emailError.textContent = 'Debe introducir una dirección de correo electrónico.';
    } else if (email.validity.typeMismatch) {
        emailError.textContent = 'El valor introducido debe ser una dirección de correo electrónico.';
    } else if (email.validitytooLong) {
        nombreError.textContent = 'Mira que hay tener paciencia para escribir tanto';
    }
}

