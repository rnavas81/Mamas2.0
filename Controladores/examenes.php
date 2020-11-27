<?php
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';
require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/GestionExamenes.php';
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
}elseif(isset ($_REQUEST['registro'])) {
    $accion = "registro";
}elseif(isset ($_REQUEST['volver'])) {
    $accion = "volver";
}elseif(isset ($_REQUEST['crear'])) {
    $accion = "crear";
}elseif(isset ($_REQUEST['modificar'])) {
    $accion = "modificar";
}elseif(isset ($_REQUEST['nuevo'])) {
    $accion = "nuevo";
}elseif(isset ($_REQUEST['nuevapregunta'])) {
    $accion = "nuevapregunta";
}elseif(isset ($_REQUEST['abrirPregunta'])) {
    $accion = "abrirPregunta";
}elseif(isset ($_REQUEST['crearPregunta'])) {
    $accion = "crearPregunta";
}elseif(isset ($_REQUEST['eliminarPregunta'])) {
    $accion = "eliminarPregunta";
}elseif(isset ($_REQUEST['editarPregunta'])) {
    $accion = "editarPregunta";
}

switch ($accion) {
    //Crea un nuevo examen
    case 'crear':
        $usuario = $_SESSION['usuario'];
        $data = json_decode($_REQUEST['datos'],true);
        if(GestionExamenes::insertExamen($data,$usuario->getId())){
            $_SESSION['MSG_INFO']="Examen creado";
            unset($_SESSION['datosFormulario']);
            $redireccion = volver();
        } else {
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al crear el examen";
            $redireccion = WEB_EXAMEN_FORMULARIO;
        }
        break;
    //Modifica un examen existente
    case 'modificar':
        $usuario = $_SESSION['usuario'];
        $id = $_REQUEST["id"];
        $data = json_decode($_REQUEST['datos'],true);
        if(GestionExamenes::updateExamen($data,$id,$usuario->getId())){
            $_SESSION['MSG_INFO']="Examen modificado";
            unset($_SESSION['datosFormulario']);
            $redireccion = volver();
        } else {
            $data['id']=$id;
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al crear el examen";
            $redireccion = WEB_EXAMEN_FORMULARIO;
        }
        break;
    // Accede al formulario para un nuevo examen
    case 'nuevo':
        $_SESSION['accesoFormulario']='crear';
        $redireccion = WEB_EXAMEN_FORMULARIO;
        break;
    //Accede al formualrio para una nueva pregunta en el almacen
    case 'nuevapregunta':
        $_SESSION['volver']=$_SERVER['HTTP_REFERER'];
        $_SESSION['accesoFormulario']='crear';
        $redireccion = WEB_PREGUNTA_FORMULARIO;
        break;
    // Accede al formualrio para editar una pregunta en el almacen
    case 'abrirPregunta':
        $id=$_REQUEST['id'];
        $pregunta = GestionExamenes::getPreguntaById($id);
        if($pregunta){
            $_SESSION['datosFormulario']=$pregunta;
            $_SESSION['volver']=$_SERVER['HTTP_REFERER'];
            $_SESSION['accesoFormulario']='modificar';
            $redireccion = WEB_PREGUNTA_FORMULARIO;
        } else {
            $_SESSION['MSG_INFO']="Error al recuperar la pregunta";
            $redireccion = $_SERVER['HTTP_REFERER'];
        }
        break;
    //Regresa a la pantalla de acceso del usuario
    case 'volver':
        unset($_SESSION['datosFormulario']);
        $redireccion = volver();
        break;
    // Crea una nueva pregunta en el almacen
    case 'crearPregunta':
        $usuario = $_SESSION['usuario'];
        $data = json_decode($_REQUEST['datos'],true);
        if(GestionExamenes::insertPreguntaAlmacen($data,$usuario->getId())){
            $_SESSION['MSG_INFO']="Pregunta creada";
            unset($_SESSION['datosFormulario']);
            $redireccion = volver();
        } else {
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al crear la pregunta";
            $redireccion = WEB_PREGUNTA_FORMULARIO;
        }
        break;
    // Modifica una pregunta del almacen
    case 'editarPregunta':
        $usuario = $_SESSION['usuario'];
        $id = $_REQUEST['id'];
        $data = json_decode($_REQUEST['datos'],true);
        if(GestionExamenes::updatePreguntaAlmacen($id,$data,$usuario->getId())){
            $_SESSION['MSG_INFO']="Pregunta modificada";
            unset($_SESSION['datosFormulario']);
            $redireccion = volver();
        } else {
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al editar la pregunta";
            $redireccion = WEB_PREGUNTA_FORMULARIO;
        }
        break;
    // Elimina una pregunta del almacen
    case 'eliminarPregunta':
        $usuario = $_SESSION['usuario'];
        $id = $_REQUEST['id'];
        if(GestionExamenes::deletePreguntaAlmacen($id,$usuario->getId())){
            $_SESSION['MSG_INFO']="Pregunta eliminada";
        } else {
            $_SESSION['MSG_INFO'] = "Error al eliminar la pregunta";
        }
        $redireccion = WEB_PREGUNTAS;
        break;
}

//Redirecciona a la página indicada en $redireccion
if($redireccion){
    header("Location: ".$redireccion);
} else {
    $redireccion = cerrarSesion();
    header("Location: ".$redireccion);
}