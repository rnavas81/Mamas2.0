<?php
/**
 * @author 
 * @create 
 */

require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/Usuario.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}

// Punto de redirección del controlador
// Si no hay punto de redirección va al punto de entrada
$redireccion = null;
//Recupera la acción
$accion = null;
if(isset($_REQUEST['accion'])){
    $accion = $_REQUEST['accion'];
} elseif(isset ($_REQUEST['accederAlumno']) || isset($_REQUEST['loginAlSubm'])) {
    $accion = "accederAlumno";
}
/*
if(isset($_REQUEST['loginAlSubm'])) {
    $dni = $_REQUEST['dniAl'];
    $entra = GestionUsuarios::canLogin($dni, $_REQUEST['pasAl']);
    echo 'Login = '.$entra;;
}
 * */

switch ($accion) {
    //Comprueba el usuario alumno
    case "accederAlumno":
        $dni = $_REQUEST['dni'];
        $password = $_REQUEST['password'];
        $usuario = GestionUsuarios::canLogin($dni, $password);
        if($usuario){
            $redireccion = WEB_ENTRADA_ALUMNOS;
            $_SESSION['usuario']=$usuario;
        }
        break;

    default:
        break;
}
