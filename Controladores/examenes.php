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
}elseif(isset ($_REQUEST['volver'])) {
    $accion = "volver";
}elseif(isset ($_REQUEST['crear'])) {
    $accion = "crear";
}elseif(isset ($_REQUEST['modificar'])) {
    $accion = "modificar";
}elseif(isset ($_REQUEST['nuevo'])) {
    $accion = "nuevo";
}

switch ($accion) {
    //Vuelve del formulario de examen
    case 'volver':
        unset($_SESSION['datosFormulario']);
        if($_SESSION['usuarioAcceso']=='alumno'){
            $redireccion = WEB_ENTRADA_ALUMNOS;
        } elseif($_SESSION['usuarioAcceso']=='profesor'){
            $redireccion = WEB_ENTRADA_PROFESORES;
        } elseif($_SESSION['usuarioAcceso']=='administrador'){
            $redireccion = WEB_ENTRADA_ADMINISTRADORES;
        } else {
            $redireccion = WEB_INDEX;
        }
        break;
    //Crea un nuevo examen
    case 'crear':
        $usuario = $_SESSION['usuario'];
        $data = json_decode($_REQUEST['datos'],true);
        if(GestionExamenes::insertExamen($data,$usuario->getId())){
            $_SESSION['MSG_INFO']="Examen creado";
            unset($_SESSION['datosFormulario']);
            if($_SESSION['usuarioAcceso']=='alumno'){
                $redireccion = WEB_ENTRADA_ALUMNOS;
            } elseif($_SESSION['usuarioAcceso']=='profesor'){
                $redireccion = WEB_ENTRADA_PROFESORES;
            } elseif($_SESSION['usuarioAcceso']=='administrador'){
                $redireccion = WEB_ENTRADA_ADMINISTRADORES;
            } else {
                $redireccion = WEB_INDEX;
            }
        } else {
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al crear el examen";
        }
        break;
    //Modifica un examen existente
    case 'modificar':
        $usuario = $_SESSION['usuario'];
        $id = $_REQUEST["id"];
        $data = json_decode($_REQUEST['datos'],true);;
        if(GestionExamenes::updateExamen($data,$id,$usuario->getId())){
            $_SESSION['MSG_INFO']="Examen creado";
            unset($_SESSION['datosFormulario']);
            if($_SESSION['usuarioAcceso']=='alumno'){
                $redireccion = WEB_ENTRADA_ALUMNOS;
            } elseif($_SESSION['usuarioAcceso']=='profesor'){
                $redireccion = WEB_ENTRADA_PROFESORES;
            } elseif($_SESSION['usuarioAcceso']=='administrador'){
                $redireccion = WEB_ENTRADA_ADMINISTRADORES;
            } else {
                $redireccion = WEB_INDEX;
            }
        } else {
            $_SESSION['datosFormulario']=$data;
            $_SESSION['MSG_INFO'] = "Error al crear el examen";
        }
        break;
    case 'nuevo':
        $_SESSION['accesoFormulario']='crear';
        $redireccion = WEB_EXAMEN_FORMULARIO;
        break;
}

//Redirecciona a la página indicada en $redireccion
if($redireccion){
    header("Location: ".$redireccion);
} else {
    $redireccion = cerrarSesion();
    header("Location: ".$redireccion);
}