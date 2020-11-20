<?php
/**
 * @author Rodrigo Navas
 * 
 * Funciones de uso vario
 */
require_once '../configuracion.php';
/**
 * Elimina las posibles variables de sesión y devuelve la dirección de la página a la que salir
 */
function cerrarSesion() {
    $paginaSalir = WEB_INDEX;
    if($_SESSION['usuarioAcceso']=='alumno'){
        $paginaSalir = WEB_INDEX;
    } elseif($_SESSION['usuarioAcceso']=='profesor'){
        $paginaSalir = WEB_INDEX;
    } elseif($_SESSION['usuarioAcceso']=='administrador'){
        $paginaSalir = WEB_ADMIN;
    } else {
        $paginaSalir = WEB_INDEX;
    }              
    unset(
        $_SESSION['rolRegistro'],
        $_SESSION['usuario'],
        $_SESSION['MSG_INFO'],
        $_SESSION['administradorTipo'],
        $_SESSION['accesoFormulario'],
        $_SESSION['datosFormulario'],
        $_SESSION['usuarioAcceso']
    );
    return $paginaSalir;
}

