<!DOCTYPE html>
<?php
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';

if(session_status()!=PHP_SESSION_ACTIVE){
        session_start();
    }
if(isset($_REQUEST['tipo'])){
    $_SESSION['rolRegistro'] = $_REQUEST['tipo'];
}
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
   $msg= $_SESSION['MSG_INFO'];
   unset($_SESSION['MSG_INFO']);
}
$usuario = null;
if(!isset($_SESSION['usuarioForm'])){
    $usuario = new Usuario(0, "");
} else {
    $usuario = $_SESSION['usuarioForm'];
}
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
                                <p class="h4 mb-4">Registro</p>
                                <p class="text-left">DNI</p>
                                <input type="text" id="registroDni" name="dni" value="<?=$usuario->getDni()?>" class="form-control mb-4" placeholder="12345678A" pattern="[0-9]{8}[A-Za-z]{1}" minlength="9" maxlength="9" required />
                                <span class="errorDni" aria-live="polite"></span>
                                <p class="text-left">Contraseña</p>
                                <input type="password" id="registroPass" name="password" class="form-control mb-4" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" minlength="6" required />
                                <span class="errorPassword" aria-live="polite"></span>
                                <p class="text-left">Nombre</p>
                                <input type="text" id="registroNombre" name="nombre" value="<?=$usuario->getNombre()?>" class="form-control mb-4" pattern="[a-zA-Z0-9]+" required />
                                <span class="errorNombre" aria-live="polite"></span>
                                <p class="text-left">Apellidos</p>
                                <input type="text" id="registroApellidos" name="apellidos" value="<?=$usuario->getApellidos()?>" class="form-control mb-4" pattern="[a-zA-Z0-9]+" required />
                                <span class="errorApellidos" aria-live="polite"></span>
                                <p class="text-left">Fecha de nacimiento</p>
                                <input type="date" id="registroFechaNac" name="fechaNacimiento" value="<?=$usuario->getFechaNacimiento()?>"class="form-control mb-4" required />
                                <span class="errorFechaNac" aria-live="polite"></span>
                                <p class="text-left">Email</p>
                                <input type="email" id="registroEmail" name="email" class="form-control mb-4" value="<?=$usuario->getEmail()?>" placeholder="ejemplo@gmail.com" required/>
                                <span class="errorEmail" aria-live="polite"></span>
                                <div>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="registro">
                                       Registrarse
                                    </button>
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
