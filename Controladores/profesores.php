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
} elseif(isset ($_REQUEST['examenesAct'])) {
    $accion = "examenesAct";
} elseif (isset ($_REQUEST['examenesDes'])) {
    $accion = "examenesDes";
}

switch ($accion) {
    case "examenesAct":
        $_SESSION['profesorTipo']= 'activos';
        $redireccion=WEB_ENTRADA_PROFESORES;
        break;
    case "examenesDes":
        $_SESSION['profesorTipo']= 'desactivados';
        $redireccion=WEB_ENTRADA_PROFESORES;
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