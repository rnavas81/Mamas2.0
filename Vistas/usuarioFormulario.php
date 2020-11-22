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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
        <?php  //Google Fonts Roboto ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
        <?php  //Bootstrap core ?>
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <?php //mdBootstrap css ?>
        <link rel="stylesheet" href="../css/mdb.min.css" />
        <?php //Estilos propios ?>
        <link rel="stylesheet" href="../css/validacion.css" /> 
    </head>

    <body onload="validacion()">
        <main class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-md-12 col-lg-6 pb-5">
                        <div class="container">
                            <form class="text-center border border-light p-5" id="formRegistro" name="formRegistro" action="<?=CTRL_USUARIOS?>" method="POST">
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
                                        case "leer":?>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="volver">
                                       Volver
                                    </button>
                                    <?php 
                                        break;
                                        case "crear":?>
                                    <button class="btn btn-primary   my-4" type="submit" name="crear">
                                       Crear
                                    </button>
                                    <button class="btn btn-danger my-4" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción para leer será volver
                                        case "modificar":?>
                                    <button class="btn btn-primary my-4" type="submit" name="modificar">
                                       Modificar
                                    </button>
                                    <button class="btn btn-danger my-4" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción por defecto será volver.
                                        default:?>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="volver">
                                       Volver
                                    </button>
                                    <?php 
                                        break;
                                    }?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>                        
        </main>
        
        <script type = "text/javascript" src="../js/validacion.js"></script>  
        <?php //jQuery ?>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <?php //Bootstrap tooltips ?>
        <script type="text/javascript" src="../js/popper.min.js"></script>
        <?php //Bootstrap core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <?php //MDB core JavaScript ?>
        <script type="text/javascript" src="../js/mdb.min.js"></script>
    </body>
</html>