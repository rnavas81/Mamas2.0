<?php

require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/GestionExamenes.php';
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
} elseif(isset ($_REQUEST['empezarExamen'])) {
    $accion = "empezarExamen";
} elseif(isset($_REQUEST['terminarExamen'])) {
    $accion ="realizarExamen";
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
    case "empezarExamen":
        $id = $_REQUEST['id'];
        $examen = GestionExamenes::getExamenById($id);
        $_SESSION['examenAct'] = $examen;
        $redireccion = WEB_EXAMEN_ALUMNO_REALIZA;
        break;
    case "realizarExamen":
        $json=($_REQUEST['respuestasFin']);
        $datos = json_decode($json);
        $idExamen = $_SESSION['examenAct']['id'];
        $usuario = ($_SESSION['usuario']);        
        $idAlumno = $usuario->getId();
        
        GestionExamenes::saveRespuestasAlumno($idAlumno, $idExamen, $datos);
        
        $redireccion = WEB_ENTRADA_ALUMNOS;        
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