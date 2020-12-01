<!DOCTYPE html>
<?php
/**
 * @author Rodrigo Navas
 * 
 * Formulario de datos para Usuario
 * Este formulario sirve para crear/modificar/leer datos de un usuario
 * 
 * La funcionalidad del formulario se determina en la variable de sesión accesoFormulario.
 * Por defecto la funcionalidad será leer.
 * 
 * Los datos del formulario se determinan en la variable de sesión datosFormulario
 * Por defecto los datos estarán vacíos.
 */
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';
require_once '../Modelos/GestionUsuarios.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
//Comprueba que hay un usuario logueado y tiene permisos de administrador
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(1)) OR header("Location: ".CTRL_BASICO);
isset($_SESSION['usuario']) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
   $msg= $_SESSION['MSG_INFO'];
   unset($_SESSION['MSG_INFO']);
}
//Recupera los datos del formulario
$datos = null;
if(isset($_SESSION['datosFormulario'])){
    $datos = $_SESSION['datosFormulario'];
} else {
    $datos = new Usuario(-1, "");
}
$accesoFormulario = 'leer';
if(isset($_SESSION['accesoFormulario'])){
    $accesoFormulario = $_SESSION['accesoFormulario'];
}
$roles = json_decode(GestionUsuarios::getRoles(),true);
?>
<html>
    <head>
        <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Mamas 2.0</title>
        <?php  //Icono ?>
        <link rel="icon" href="../img/mdb-favicon.ico" type="image/x-icon" />
        <?php //Font Awesome ?>
        <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
        <?php  //Bootstrap core ?>
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <?php //mdBootstrap css ?>
        <link rel="stylesheet" href="../css/mdb.min.css" />
        <!-- Para la cabecera -->
        <link rel="stylesheet" href="../css/sidebar.css" />
        <?php //Estilos propios ?>
        <link rel="stylesheet" href="../css/style.css" /> 
        
    </head>

    <body class="fondo-pantalla">
        <?php
        $tipoOpciones="administradorDashboard";
        require_once '../Componentes/cabecera.php';
        ?>
        <main class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-md-12 col-lg-6 pb-5">
                        <div class="container">
                            <form class="text-center border border-light p-5 white" id="formRegistro" name="formRegistro" action="<?=CTRL_USUARIOS?>" method="POST">
                                <input type="hidden" name="id" value="<?=$datos->getId()?>"/>
                                <!-- <p class="h4 mb-4">Registro</p> -->
                                <p class="text-left">DNI</p>
                                <input type="text" id="registroDni" name="dni" value="<?=$datos->getDni()?>" 
                                       class="form-control mb-4 w-auto" placeholder="12345678A" pattern="[0-9]{8}[A-Za-z]{1}" 
                                       minlength="9" maxlength="9" <?=$accesoFormulario=='leer'?'disabled':'required'?>/>
                                <span class="errorDni" aria-live="polite"></span>
                                <p class="text-left">Nombre</p>
                                <input type="text" id="registroNombre" name="nombre" value="<?=$datos->getNombre()?>" 
                                       class="form-control mb-4" <?=$accesoFormulario=='leer'?'disabled':'required'?> />
                                <span class="errorNombre" aria-live="polite"></span>
                                <p class="text-left">Apellidos</p>
                                <input type="text" id="registroApellidos" name="apellidos" value="<?=$datos->getApellidos()?>" 
                                       class="form-control mb-4" <?=$accesoFormulario=='leer'?'disabled':'required'?> />
                                <span class="errorApellidos" aria-live="polite"></span>
                                <p class="text-left">Email</p>
                                <input type="email" id="registroEmail" name="email" class="form-control mb-4" value="<?=$datos->getEmail()?>"
                                       placeholder="email@nomail.com" <?=$accesoFormulario=='leer'?'disabled':'required'?>/>
                                <span class="errorEmail" aria-live="polite"></span>
                                <p class="text-left">Fecha de nacimiento</p>
                                <input type="date" id="registroFechaNac" name="fechaNacimiento" value="<?=$datos->getFechaNacimiento()?>" 
                                       class="form-control mb-4 w-auto" <?=$accesoFormulario=='leer'?'disabled':'required'?> />
                                <span class="errorFechaNac" aria-live="polite"></span>
                                <?php if($accesoFormulario!='leer'){?>
                                <p class="text-left">Contraseña</p>
                                <input type="password" id="registroPass" name="password" class="form-control mb-4" 
                                       pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" minlength="6" <?=$accesoFormulario=='crear'?'required':''?> />
                                <span class="errorPassword" aria-live="polite"></span>
                                <p class="text-left">Roles</p>
                                <div class="d-flex form-group justify-content-around flex-wrap">
                                    <?php
                                    foreach ($roles as $rol) {?>
                                    <div class="form-check">
                                      <input class="form-check-input" type="checkbox" name="roles[]"
                                             value="<?=$rol['id']?>" <?=$datos->hasRol($rol['id'])?'checked':''?>
                                      <label class="form-check-label" for="gridCheck">
                                        <?=$rol['nombre']?>
                                      </label>
                                    </div>
                                    <?php }?>
                                </div>
                                
                                <?php }?>
                                <div class="d-flex justify-content-around">
                                    <?php
                                    switch ($accesoFormulario) {
                                        case "crear":?>
                                    <button class="btn primary-color my-4" type="submit" name="crear">Crear</button>
                                    <?php 
                                        break;
                                        //La acción para leer será volver
                                        case "modificar":?>
                                    <button class="btn primary-color my-4" type="submit" name="modificar">Modificar</button>
                                    <?php 
                                        break;
                                        //La acción por defecto será volver.
                                        default:
                                        break;
                                    }?><a class="btn secondary-color primary-dark-color-text my-4" href="<?=WEB_ENTRADA_ADMINISTRADORES?>">Volver</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>                        
        </main>
        
        <?php //jQuery ?>
        <script type="text/javascript" src="../js/jquery/jquery.min.js"></script>
        <?php //Bootstrap tooltips ?>
        <script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
        <?php //Bootstrap core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/bootstrap.min.js"></script>
        <?php //MDB core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/mdb.min.js"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
        <!-- Your custom scripts (optional) -->
        <script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
        <!-- Script de validación -->        
        <script type = "text/javascript" src="../js/usuariosValidacion.js"></script>  
    </body>
</html>
