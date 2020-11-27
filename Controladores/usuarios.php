<?php
require_once '../configuracion.php';
require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/Usuario.php';
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
}elseif(isset ($_REQUEST['modificarPerfil'])) {
    $accion = "modificarPerfil";
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
        $usuario = new Usuario(0, $dni, $nombre, $apellidos, $fecha, $email,$rol);
        unset($_SESSION['rolRegistro']);
        $duplicado = [
            'dni'=> GestionUsuarios::isDuplicado("dni", $dni),
            'email'=> GestionUsuarios::isDuplicado("email", $email)
        ];
        $valid = $duplicado['dni']==0 && $duplicado['email']==0;
        if($valid) {
            $response = GestionUsuarios::insertUsuario($usuario, $password);
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
    //Vuelve del formulario de usuario
    case 'volver':
        unset($_SESSION['datosFormulario']);
        $redireccion = volver();
        break;
    //Crea un nuevo usuario
    case 'crear':
        $_REQUEST['roles']= implode(",", $_REQUEST['roles']);
        $nuevo = GestionUsuarios::formarUsuario($_REQUEST);
        $duplicado = [
            'dni'=> GestionUsuarios::isDuplicado("dni", $nuevo->getDni()),
            'email'=> GestionUsuarios::isDuplicado("email", $nuevo->getEmail())
        ];
        $valid = $duplicado['dni']==0 && $duplicado['email']==0;
        if($valid) {
            if(GestionUsuarios::insertUsuario($nuevo)){
                $_SESSION['MSG_INFO']="Usuario creado";
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
                $_SESSION['MSG_INFO']="Error al crear el usuario";
                $_SESSION['datosFormulario']=$nuevo;
                $redireccion = WEB_USUARIO_FORMULARIO;
            }
        }  else {
            $_SESSION['usuarioForm'] = $nuevo;
            if($duplicado['dni']>0) {
                $_SESSION['MSG_INFO']="<br>Error en el DNI";
                $redireccion = WEB_USUARIO_FORMULARIO;
            }
            if($duplicado['email']>0) {
                $_SESSION['MSG_INFO'].="<br>Error en el email";
                $redireccion = WEB_USUARIO_FORMULARIO;
            }
        }
        break;
    //Modifica un usuario existente
    case 'modificar':
        $id = $_REQUEST['id'];
        $user = GestionUsuarios::getUsuarioById($id);
        if($user){
            $dni = $_REQUEST['dni'];
            $password = $_REQUEST['password'];
            $nombre = $_REQUEST['nombre'];
            $apellidos = $_REQUEST['apellidos'];
            $fechaNacimiento = $_REQUEST['fechaNacimiento'];
            $email = $_REQUEST['email'];
            $roles = $_REQUEST['roles'] ;
            $user->setDni($dni);
            $user->setEmail($email);
            $user->setNombre($nombre);
            $user->setApellidos($apellidos);
            $user->setRoles($roles);
            $user->setFechaNacimiento($fechaNacimiento);
            $duplicado = [
                'dni'=> GestionUsuarios::isDuplicado("dni", $dni,$id),
                'email'=> GestionUsuarios::isDuplicado("email", $email,$id)
            ];
            $valid = $duplicado['dni']==0 && $duplicado['email']==0;
            if($valid) {
                if(GestionUsuarios::updateUsuario($user,$password)){
                    $_SESSION['MSG_INFO']="Usuario modificado";
                    unset($_SESSION['datosFormulario']);
                    $redireccion = volver();
                } else {
                    $_SESSION['MSG_INFO']="Error al actualizar el usuario";
                    $_SESSION['datosFormulario']=$user;
                    $redireccion = WEB_USUARIO_FORMULARIO;
                }
            } else {
                $_SESSION['MSG_INFO']="Error al recuperar el usuario";
                $_SESSION['datosFormulario']=$user;
                $redireccion = WEB_USUARIO_FORMULARIO;
            }
            
        }
        break;
    case 'modificarPerfil':
        $user = $_SESSION['usuario'];
        $id = $user->getId();
        if($user){
            $dni = $_REQUEST['dni'];
            $password = $_REQUEST['password'];
            $nombre = $_REQUEST['nombre'];
            $apellidos = $_REQUEST['apellidos'];
            $fechaNacimiento = $_REQUEST['fechaNacimiento'];
            $email = $_REQUEST['email'];
            $user->setDni($dni);
            $user->setEmail($email);
            $user->setNombre($nombre);
            $user->setApellidos($apellidos);
            $user->setFechaNacimiento($fechaNacimiento);
            $duplicado = [
                'dni'=> GestionUsuarios::isDuplicado("dni", $dni,$id),
                'email'=> GestionUsuarios::isDuplicado("email", $email,$id)
            ];
            $valid = $duplicado['dni']==0 && $duplicado['email']==0;
            if($valid) {
                if(GestionUsuarios::updateUsuario($user,$password)){
                    $_SESSION['MSG_INFO']="Mis datos actualizados";
                    $_SESSION['usuario']=$user;
                    unset($_SESSION['datosFormulario']);
                    $redireccion =volver();
                } else {
                    $_SESSION['MSG_INFO']="Error al actualizar mis datos";
                    $_SESSION['datosFormulario']=$user;
                    $redireccion = WEB_FORMULARIO_PERFIL;
                }
            } else {
                $_SESSION['MSG_INFO']="Error al recuperar mis datos";
                $_SESSION['datosFormulario']=$user;
                $redireccion = WEB_FORMULARIO_PERFIL;
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