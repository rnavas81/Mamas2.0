<?php

require_once '../Modelos/GestionUsuarios.php';
require_once '../Funciones/varias.php';

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
} elseif(isset ($_REQUEST['examenesPendientes'])) {
    $accion = "examenesPendientes";
} elseif (isset ($_REQUEST['examenesRealizados'])) {
    $accion = "examenesRealizados";
}

switch ($accion) {
    case "examenesPendientes":
        $_SESSION['alumnoTipo']= 'activos';
        $redireccion=WEB_ENTRADA_ALUMNOS;
        break;
    case "examenesRealizados":
        $_SESSION['alumnoTipo']= 'desactivados';
        $redireccion=WEB_ENTRADA_ALUMNOS;
        break;
    default:
        $redireccion = cerrarSesion();
        break;
}

if($redireccion){
    header("Location: ".$redireccion);
} else {
    $redireccion = cerrarSesion();
    header("Location: ".$redireccion);
}