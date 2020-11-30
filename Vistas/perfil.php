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
isset($_SESSION['usuario']) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
   $msg= $_SESSION['MSG_INFO'];
   unset($_SESSION['MSG_INFO']);
}
//Recupera los datos del formulario
$datos = null;
$usuario = isset($_SESSION['datosFormulario'])?$_SESSION['datosFormulario']:$_SESSION['usuario'];

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
                                <p class="text-left">DNI</p>
                                <input type="text" id="registroDni" name="dni" value="<?=$usuario->getDni()?>" 
                                       class="form-control mb-4 w-auto" placeholder="12345678A" pattern="[0-9]{8}[A-Za-z]{1}" 
                                       minlength="9" maxlength="9" <?=$usuario->getDni()?>/>
                                <span class="errorDni" aria-live="polite"></span>
                                <p class="text-left">Nombre</p>
                                <input type="text" id="registroNombre" name="nombre" value="<?=$usuario->getNombre()?>" 
                                       class="form-control mb-4" required/>
                                <span class="errorNombre" aria-live="polite"></span>
                                <p class="text-left">Apellidos</p>
                                <input type="text" id="registroApellidos" name="apellidos" value="<?=$usuario->getApellidos()?>" 
                                       class="form-control mb-4" required/>
                                <span class="errorApellidos" aria-live="polite"></span>
                                <p class="text-left">Email</p>
                                <input type="email" id="registroEmail" name="email" class="form-control mb-4" value="<?=$usuario->getEmail()?>"
                                       placeholder="email@nomail.com" required/>
                                <span class="errorEmail" aria-live="polite"></span>
                                <p class="text-left">Fecha de nacimiento</p>
                                <input type="date" id="registroFechaNac" name="fechaNacimiento" value="<?=$usuario->getFechaNacimiento()?>" 
                                       class="form-control mb-4 w-auto" required />
                                <span class="errorFechaNac" aria-live="polite"></span>
                                <p class="text-left">Contraseña</p>
                                <input type="password" id="registroPass" name="password" class="form-control mb-4" 
                                       pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" minlength="6"/>
                                <span class="errorPassword" aria-live="polite"></span>
                                <div class="d-flex justify-content-around">
                                    <button class="btn primary-color my-4" type="submit" name="modificarPerfil">
                                       Modificar
                                    </button>
                                    <a class="btn secondary-color primary-dark-color-text my-4" href="<?= $_SESSION['volver'] ?>">
                                        Volver
                                    <a/>
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
