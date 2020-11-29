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
        $_SESSION['administradorTipo'],
        $_SESSION['accesoFormulario'],
        $_SESSION['datosFormulario'],
        $_SESSION['datosExamen'],
        $_SESSION['usuarioAcceso']
    );
    return $paginaSalir;
}

/**
 * Genera una cadena alfanumérica de longitud determinada
 * @param Number $length Longitud de la cadena
 * @return String Cadena alfanumérica
 */
function aleatorioAlphanumerico($length=8) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $random = "";
    for ($index = 0; $index < $length; $index++) {
        $i = random_int(0, strlen($permitted_chars)-1);
        $random.= substr($permitted_chars,$i,1);
    }
    return $random;    
}
/**
 * Devuelve la ruta de vuelta del controlador 
 * si existe en sesión.
 * @return String
 */
function volver(){
    var_dump($_SESSION);
    $volver = null;
    if(isset($_SESSION['volver'])){
        $volver = $_SESSION['volver'];
        unset($_SESSION['volver']);
    } else {
        if($_SESSION['usuarioAcceso']==TIPO_ALUMNO){
            $volver = WEB_ENTRADA_ALUMNOS;
        } elseif($_SESSION['usuarioAcceso']==TIPO_PROFESOR){
            $volver = WEB_ENTRADA_PROFESORES;
        } elseif($_SESSION['usuarioAcceso']==TIPO_ADMINISTRADOR){
            $volver = WEB_ENTRADA_ADMINISTRADORES;
        } else {
            $volver = WEB_INDEX;
        }
    }
    return $volver;
    
}