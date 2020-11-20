function validacion() 
{
    const form = document.getElementById('formRegistro')[0];
    
    const dni = document.getElementById('registroDni');
    const dniError = document.querySelector('#registroDni + span.errorDni');
    
    const paswword = document.getElementById('registroPass');
    const paswwordError = document.querySelector('#registroPass + span.errorPassword');
    
    const nombre = document.getElementById('registroNombre');
    const nombreError = document.querySelector('#registroNombre + span.errorNombre');
    
    const apellidos = document.getElementById('registroApellidos');
    const apellidosError = document.querySelector('#registroApellidos + span.errorApellidos');
    
    const fechaNac = document.getElementById('registroFechaNac');
    const fechaNacError = document.querySelector('#registroFechaNac + span.errorFechaNac');
    
    const email = document.getElementById('registroEmail');
    const emailError = document.querySelector('#registroEmail + span.errorEmail');
    
    form.addEventListener('submit', function (event) {

        if (!email.validity.valid) {
            showError();
            event.preventDefault();
        }
        
        if (!dni.validity.valid) {
            showError();
            event.preventDefault();
        }
        
        if (!nombre.validity.valid) {
            showError();
            event.preventDefault();
        }
        
        if (!apellidos.validity.valid) {
            showError();
            event.preventDefault();
        }
        
        if (!fechaNac.validity.valid) {
            showError();
            event.preventDefault();
        }
        
        if (!paswword.validity.valid) {
            showError();
            event.preventDefault();
        }
    });
    
    function showError() {
        
        if (email.validity.valueMissing) {
            emailError.textContent = 'Debe introducir una dirección de correo electrónico.';
        } else if (email.validity.typeMismatch) {
            emailError.textContent = 'El valor introducido debe ser una dirección de correo electrónico.';
        } 
        
        if (paswword.validity.valueMissing) {
            paswwordError.textContent = 'Debe introducir una contraseña';
        } else if (paswword.validity.patternMismatch) {            
            paswwordError.textContent = 'La contraseña debe incluir minimo una mayuscula, una minuscula y un número';
        } else if (paswword.validity.tooShort) {
            paswwordError.textContent = 'La cotraseña debe tener al menos ${ paswword.minLength } caracteres; ha introducido ${ paswword.value.length }.';
        } 
        
        if (dni.validity.valueMissing) {
            alert('entra');
            dniError.textContent = 'Debe introducir un DNI';
        } else if (dni.validity.patternMismatch) {            
            dniError.textContent = 'El DNI solo puede contener numeros y una única letra';
        } else if (dni.validity.tooShort) {
            dniError.textContent = 'El dni debe tener ${ dni.minLength } caracteres; ha introducido ${ dni.value.length }.';
        } 
        
        if (nombre.validity.valueMissing) {
            nombreError.textContent = 'Debe introducir un nombre';
        } else if (nombre.validity.patternMismatch) {            
            nombreError.textContent = 'Ha introduciodo carácteres no válidos';
        } 
        
        if (apellidos.validity.valueMissing) {
            apellidosError.textContent = 'Debe introducir un apellido';
        } else if (apellidos.validity.patternMismatch) {            
            apellidosError.textContent = 'Ha introduciodo carácteres no válidos';
        } 
        
        if (fechaNac.validity.valueMissing) {
            fechaNacError.textContent = 'Debe introducir una fecha de nacimiento';
        }
        
        dniError.className = 'error active';
        nombreError.className = 'error active';
        apellidosError.className = 'error active';
        fechaNacError.className = 'error active';
        emailError.className = 'error active';
        paswwordError.className = 'error active';
    };
}

