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
} elseif(isset ($_REQUEST['accederAlumnos'])) {
    $accion = "accederUsuario";
    $aux = TIPO_ALUMNO;
} elseif(isset ($_REQUEST['accederProfesores'])) {
    $accion = "accederUsuario";
    $aux = TIPO_PROFESOR;
} elseif(isset ($_REQUEST['accederAdminstradores'])) {
    $accion = "accederUsuario";
    $aux = TIPO_ADMINISTRADOR;
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
    case "accederUsuario":
        $dni = $_REQUEST['dni'];
        $password = $_REQUEST['password'];
        $usuario = GestionUsuarios::canLogin($dni, $password);
        if($usuario){
            try {
                $_SESSION['usuarioAcceso'] = $aux;
                if($aux==TIPO_ALUMNO && $usuario->hasRol(ROL_ALUMNO)){
                    $redireccion = WEB_ENTRADA_ALUMNOS;
                    $_SESSION['usuario']= $usuario;
                } elseif($aux==TIPO_PROFESOR && $usuario->hasRol(ROL_PROFESOR)){
                    $redireccion = WEB_ENTRADA_PROFESORES;
                    $_SESSION['usuario']= $usuario;
                } elseif($aux==TIPO_ADMINISTRADOR && $usuario->hasRol(ROL_ADMINISTRADOR)){
                    $redireccion = WEB_ENTRADA_ADMINISTRADORES;
                    $_SESSION['usuario']= $usuario;
                } else {
                    $redireccion = WEB_INDEX;
                    unset($_SESSION['usuarioAcceso']);
                    $_SESSION['MSG_INFO']="No tiene permisos para acceder";
                }                
            } catch (Exception $exc) {
                unset($_SESSION['usuarioAcceso']);
                $redireccion = WEB_INDEX;
                $_SESSION['MSG_INFO']="Error en el acceso";
            }
        } else {
            $_SESSION['MSG_INFO']="Error al acceder al sistema";
            $redireccion = WEB_INDEX;
        }
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