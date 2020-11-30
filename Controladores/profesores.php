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
} elseif (isset ($_REQUEST['activarExamen'])) {
    $accion = "activacionExamen";
    $aux=1;
} elseif (isset ($_REQUEST['desactivarExamen'])) {
    $accion = "activacionExamen";
    $aux=0;
} elseif (isset ($_REQUEST['eliminarExamen'])) {
    $accion = "eliminarExamen";
} elseif (isset ($_REQUEST['editarExamen'])) {
    $accion = "editarExamen";
} elseif (isset ($_REQUEST['asignarExamen'])) {
    $accion = "asignarExamen";
} elseif (isset ($_REQUEST['examenes'])) {
    $accion = "examenes";
} elseif (isset ($_REQUEST['preguntas'])) {
    $accion = "preguntas";
} elseif (isset ($_REQUEST['examenesActivos'])) {
    $accion = "examenesActivos";
} elseif (isset ($_REQUEST['verExamen'])) {
    $accion = "verExamen";
} elseif (isset ($_REQUEST['corregirExamen'])) {
    $accion = "corregirExamen";
} elseif (isset ($_REQUEST['terminarCorrecion'])) {
    $accion = "terminarCorrecion";
}

switch ($accion) {
    case "examenes":
        $redireccion=WEB_ENTRADA_PROFESORES;
        break;
    case "preguntas":
        $_SESSION['profesorTipo']= 'desactivados';
        $redireccion=WEB_PREGUNTAS;
        break;
    case "activacionExamen":
        GestionExamenes::activacionExamen($_REQUEST['id'],$aux);
        $redireccion=WEB_ENTRADA_PROFESORES;
        break;
    case "eliminarExamen":
        GestionExamenes::deleteExamen($_REQUEST['id']);
        $redireccion=WEB_ENTRADA_PROFESORES;
        break;
    case "editarExamen":
        $id = $_REQUEST['id'];
        $examen = GestionExamenes::getExamenById($id);
        if($examen){
            $redireccion = WEB_EXAMEN_FORMULARIO;
            $_SESSION['datosFormulario']=$examen;
            $_SESSION['accesoFormulario']="modificar";
        } else {
            $_SESSION['MSG_INFO']="Error al recuperar el examen";
            $redireccion=WEB_ENTRADA_PROFESORES;
        }
        break;
    case "asignarExamen":
        $id = $_REQUEST["id"];
        $examen = GestionExamenes::getExamenById($id);
        if($examen){
            $redireccion = WEB_ASIGNAR_EXAMEN;
            $_SESSION['datosExamen']=$examen;
            $_SESSION['accesoFormulario']="modificar";
        } else {
            $_SESSION['MSG_INFO']="Error al recuperar el examen";
            $redireccion=WEB_ENTRADA_PROFESORES;
        }
        break;
    case "examenesActivos":
        $redireccion = WEB_EXAMEN_ACTIVO_PROFESOR;
        break;
    case "verExamen":        
        $examen = GestionExamenes::getExamenById($_REQUEST['id']);
        $_SESSION['examenAct'] = $examen;
        $redireccion = WEB_EXAMEN_ALUMNOS_EXAMEN_PROFESOR;
        break;
    case "corregirExamen":        
        $_SESSION['idAlumnoAct'] = $_REQUEST['id'];
        $redireccion = WEB_EXAMEN_CORREGIR;
        break;
    case "terminarCorrecion":
        $idAlumno = $_SESSION['idAlumnoAct'];
        $idExamen = $_SESSION['examenAct']['id'];
        $nota = $_REQUEST['notasFin'];
        GestionExamenes::setNotaExamen($idAlumno, $idExamen, $nota);
        $redireccion = WEB_EXAMEN_ALUMNOS_EXAMEN_PROFESOR;
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