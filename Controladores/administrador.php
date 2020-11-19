<?php
/**
 * @author 
 * @create 
 */

require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/Usuario.php';
require_once '../Funciones/varias.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}

//CONSTANTES
const ROL_ADMINISTRADOR = 1;
const ROL_PROFESOR = 2;
const ROL_ALUMNO = 3;
const TIPO_ALUMNO = "alumno";
const TIPO_PROFESOR = "profesor";
const TIPO_ADMINISTRADOR = "administrador";


// Punto de redirección del controlador
// Si no hay punto de redirección va al punto de entrada
$redireccion = null;
//Recupera la acción
$accion = null;
//Variable de datos auxiliares
$aux=null;
if(isset($_REQUEST['accion'])){
    $accion = $_REQUEST['accion'];
} elseif(isset ($_REQUEST['editarUsuarioFormulario'])) {
    $accion = "editarUsuarioFormulario";
} elseif(isset ($_REQUEST['agregarUsuarioFormulario'])) {
    $accion = "agregarUsuarioFormulario";
} elseif(isset ($_REQUEST['eliminarUsuario'])) {
    $accion = "eliminarUsuario";
} elseif(isset ($_REQUEST['datosAdministradores'])) {
    $accion = "datosAdministradores";
} elseif(isset ($_REQUEST['datosProfesores'])) {
    $accion = "datosProfesores";
} elseif(isset ($_REQUEST['datosAlumnos'])) {
    $accion = "datosAlumnos";
}

switch ($accion) {
    //Abre el formulario para crear un nuevo usuario
    case "agregarUsuarioFormulario":
        break;
    //Recupera el usuario y abre el formulario para editarlo
    case "editarUsuarioFormulario":
        break;
    case "eliminarUsuario":
        break;
    case "datosAdministradores":
        $_SESSION['administradorTipo']='administradores';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    case "datosProfesores":
        $_SESSION['administradorTipo']='profesores';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    case "datosAlumnos":
        $_SESSION['administradorTipo']='alumnos';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    // Salir del sistema
    case "salir":
    default:
        $redireccion = cerrarSesion();
        break;
}

//Redirecciona a la página indicada en $redireccion
if($redireccion){
    header("Location: ".$redireccion);
} else {
    $redireccion = cerrarSesion();
    header("Location: ".$redireccion);
}