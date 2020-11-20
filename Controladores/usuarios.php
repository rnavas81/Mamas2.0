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
        $rol = [];   
        if($_SESSION['rolRegistro'] === 'profesor') {
            $rol[] = 2;
        } elseif ($_SESSION['rolRegistro'] === 'alumno') {
            $rol[] = 3;        
        }
        $usuario = new Usuario(0, $dni, $nombre, $apellidos, $fecha, $email,$rol);
        unset($_SESSION['rolRegistro']);
        $duplicado = [
            'dni'=> GestionUsuarios::isDuplicado("dni", $dni),
            'email'=> GestionUsuarios::isDuplicado("email", $email)
        ];
        $valid = $duplicado['dni']==0 && $duplicado['email']==0;
        if($valid) {
            $response = GestionUsuarios::registraUsuario($usuario, $password);
        } else {
            $_SESSION['usuarioForm'] = $usuario;
            if($duplicado['dni']>0) {
                $_SESSION['MSG_INFO']="<br>Error en el DNI";
                $redireccion = WEB_REGISTRAR;
            }
            if($duplicado['email']>0) {
                $_SESSION['MSG_INFO'].="<br>Error en el email";
                $redireccion = WEB_REGISTRAR;
            }
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