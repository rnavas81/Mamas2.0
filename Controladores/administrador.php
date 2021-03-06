<?php
/**
 * @author Rodrigo Navas / Darío León
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/Usuario.php';
require_once '../Funciones/varias.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}


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
} elseif(isset ($_REQUEST['datosNuevos'])) {
    $accion = "datosNuevos";
}

switch ($accion) {
    //Abre el formulario para crear un nuevo usuario
    case "agregarUsuarioFormulario":
        $_SESSION['accesoFormulario']='crear';
        $_SESSION['datosFormulario']=new Usuario(-1, "");
        $redireccion = WEB_USUARIO_FORMULARIO;
        break;
    //Recupera el usuario y abre el formulario para editarlo
    case "editarUsuarioFormulario":
        $id = $_REQUEST['id'];
        $datos = GestionUsuarios::getUsuarioById($id);
        if($datos){
            $_SESSION['accesoFormulario']='modificar';
            $_SESSION['datosFormulario']=$datos;
            $redireccion = WEB_USUARIO_FORMULARIO;
        } else {
            $_SESSION['MSG_INFO']="Error al recuperar al usuario";
            $redireccion = volver();
        }
        break;
    //Elimina un usuario por id
    case "eliminarUsuario":
        $id = $_REQUEST['id'];
        $usuario = $_SESSION['usuario'];
        if($id != $usuario->getId()) {
            $datos = GestionUsuarios::getUsuarioById($id);
            if($datos){
                if(GestionUsuarios::eliminarUsuario($id)){
                    $_SESSION['MSG_INFO']="Usuario eliminado";
                } else {
                    $_SESSION['MSG_INFO']="Error al eliminar el usuario";
                }
            } else {
                $_SESSION['MSG_INFO']="Error al recuperar al usuario";
            }
            $redireccion = volver();
        } else {
            $_SESSION['MSG_INFO']="No puedes eliminar tu propio usuario";
            $redireccion = WEB_ENTRADA_ADMINISTRADORES;
        }
        break;
    //Redirige a entrada de administradores para mostrar la lista de usuarios administradores
    case "datosAdministradores":
        $_SESSION['administradorTipo']='administradores';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    //Redirige a entrada de administradores para mostrar la lista de usuarios profesores
    case "datosProfesores":
        $_SESSION['administradorTipo']='profesores';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    //Redirige a entrada de administradores para mostrar la lista de usuarios alumnos
    case "datosAlumnos":
        $_SESSION['administradorTipo']='alumnos';
        $redireccion=WEB_ENTRADA_ADMINISTRADORES;
        break;
    //Redirige a entrada de administradores para mostrar la lista de usuarios sin ningún rol
    case "datosNuevos":
        $_SESSION['administradorTipo']='nuevos';
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