<?php
/**
 * @author Rodrigo Navas / Darío León
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/Correo.php';
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
} elseif(isset ($_REQUEST['acceder'])) {
    $accion = "acceder";
} elseif(isset ($_REQUEST['accederMulti'])) {
    $accion = "accederMulti";
} elseif(isset ($_REQUEST['accederAlumnos'])) {
    $accion = "accederUsuario";
    $aux = TIPO_ALUMNO;
} elseif(isset ($_REQUEST['accederProfesores'])) {
    $accion = "accederUsuario";
    $aux = TIPO_PROFESOR;
} elseif(isset ($_REQUEST['accederAdminstradores'])) {
    $accion = "accederUsuario";
    $aux = TIPO_ADMINISTRADOR;
} elseif(isset ($_REQUEST['recuperarPass'])) {
    $accion = "recuperarPass";
}
/*
if(isset($_REQUEST['loginAlSubm'])) {
    $dni = $_REQUEST['dniAl'];
    $entra = GestionUsuarios::canLogin($dni, $_REQUEST['pasAl']);
    echo 'Login = '.$entra;;
}
 * */
switch ($accion) {
    //Comprueba el usuario y el tipo de acceso
    case "acceder":
        $dni = $_REQUEST['dni'];
        $password = $_REQUEST['password'];
        $usuario = GestionUsuarios::canLogin($dni, $password);
        if($usuario){
            $_SESSION['usuario']= $usuario;
            switch (count($usuario->getRoles())){
                case 0:
                    $_SESSION['MSG_INFO']="Su perfil aún no está autorizado.\nPongase en contacto con el administrador";
                    $redireccion = WEB_INDEX;
                    break;
                case 1:
                    if($usuario->hasRol(ROL_ADMINISTRADOR)) {
                        $redireccion = WEB_ENTRADA_MULTI;
                    } elseif($usuario->hasRol(ROL_PROFESOR)){
                        $_SESSION['usuarioAcceso']=TIPO_PROFESOR;
                        $redireccion = WEB_ENTRADA_PROFESORES;
                    } elseif($usuario->hasRol(ROL_ALUMNO)) {
                        $_SESSION['usuarioAcceso']=TIPO_ALUMNO;
                        $redireccion = WEB_ENTRADA_ALUMNOS;
                    } else {
                        $redireccion = WEB_INDEX;
                    }
                    break;
                case 2:
                case 3:
                    $redireccion = WEB_ENTRADA_MULTI;
                    break;
                default:
                    $redireccion = WEB_INDEX;
                    break;
            }
        } else {
            $_SESSION['MSG_INFO']="Error al acceder al sistema";
            $redireccion = WEB_INDEX;
        }
        break;
    // Genera una nueva contraseña y la envia al usuario
    case "recuperarPass":
        $email = $_REQUEST['email'];
        $nueva = aleatorioAlphanumerico(16);
        GestionUsuarios::setUsuarioPasswordByEmail($email,$nueva);
        $asunto = "Cambio de contraseña en el gestor de tareas";
        $cuerpo = "<h2>Se ha cambiado su contraseña en la plataforma</h2>"
                . "<p>Si usted no ha solicitado un cambio de contraseña pongase en contacto con el administrador de la plataforma</p>"
                . "<h3>Su nueva contraseña es: $nueva</h3>";
        Correo::enviar($email,$asunto,$cuerpo);
        $_SESSION['MSG_INFO']="Nueva clave generada y enviada";
        $redireccion = WEB_INDEX;
        break;
    // Acceso desde elección de rol del sistema
    case "accederMulti":        
        $usuario = $_SESSION['usuario'];
        $aux = $_REQUEST['acceso'];
        if($aux==ROL_ALUMNO && $usuario->hasRol(ROL_ALUMNO)){
            $_SESSION['usuarioAcceso'] = TIPO_ALUMNO;
            $redireccion = WEB_ENTRADA_ALUMNOS;
        } elseif($aux==ROL_PROFESOR && ($usuario->hasRol(ROL_PROFESOR) || $usuario->hasRol(ROL_ADMINISTRADOR))){
            $_SESSION['usuarioAcceso'] = TIPO_PROFESOR;
            $redireccion = WEB_ENTRADA_PROFESORES;
        } elseif($aux==ROL_ADMINISTRADOR && $usuario->hasRol(ROL_ADMINISTRADOR)){
            $_SESSION['usuarioAcceso'] = TIPO_ADMINISTRADOR;
            $redireccion = WEB_ENTRADA_ADMINISTRADORES;
        } else {
            unset($_SESSION['usuarioAcceso']);
            $_SESSION['MSG_INFO']="No tiene permisos para acceder";
            $redireccion = WEB_INDEX;
        }
        break;
    // Salir del sistema
    case "editarPrefil":
        $_SESSION['volver']=$_SERVER['HTTP_REFERER'];
        $redireccion = WEB_FORMULARIO_PERFIL;
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