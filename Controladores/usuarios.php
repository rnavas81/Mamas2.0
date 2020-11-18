<?php

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

// Punto de redirección del controlador
// Si no hay punto de redirección va al punto de entrada
$redireccion = null;
//Recupera la acción
$accion = null;
//Variable de datos auxiliares
$aux=null;
if(isset($_REQUEST['accion'])){
    $accion = $_REQUEST['accion'];
}elseif(isset ($_REQUEST['registro'])) {
    $accion = "registro";
}

switch ($accion) {
    //Registro de usuario
    case "registro":
        $dni = $_REQUEST['dni'];
        $password = $_REQUEST['password'];
        $nombre = $_REQUEST['nombre'];
        $apellidos = $_REQUEST['apellidos'];
        $fecha = $_REQUEST['fechaNacimiento'];
        $email = $_REQUEST['email'];
        try {
            GestionUsuarios::registraUsuario($dni, $password, $nombre, $apellidos, $fecha, $email, $_SESSION['rolRegistro']);
        } catch (Exception $ex) {
            $redireccion = WEB_REGISTRAR;
            $_SESSION['MSG_INFO']="Error en el registro";
        }
        
    break;
}

//Redirecciona a la página indicada en $redireccion
if($redireccion){
    header("Location: ".$redireccion);
} else {
    $redireccion = cerrarSesion();
    header("Location: ".$redireccion);
}