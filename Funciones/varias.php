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
    unset(
        $_SESSION['rolRegistro'],
        $_SESSION['usuario'],
        $_SESSION['MSG_INFO'],
        $_SESSION['administradorTipo']
    );
    return $paginaSalir;
}

