<!DOCTYPE html>
<?php
require_once '../configuracion.php';
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    
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
        <link rel="stylesheet" href="../css/style.css" /> 
    </head>
    
    <?php 
    if(session_status()!=PHP_SESSION_ACTIVE){
        session_start();
    }
    $_SESSION['rolRegistro'] = $_REQUEST['tipo'];
    ?>
    
    <body>
        <main class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-md-12 col-lg-6 pb-5">
                        <div class="container">
                            <form class="text-center border border-light p-5" action="<?=CTRL_USUARIOS?>" method="POST">
                                <p class="h4 mb-4">Registro</p>
                                <p class="text-left">DNI</p>
                                <input type="text" id="registroDni" name="dni" class="form-control mb-4" placeholder="12345678A" required />
                                <p class="text-left">Contraseña</p>
                                <input type="password" id="registroPass" name="password" class="form-control mb-4" required />
                                <p class="text-left">Nombre</p>
                                <input type="text" id="registroNombre" name="nombre" class="form-control mb-4" required />
                                <p class="text-left">Apellidos</p>
                                <input type="text" id="registroApellidos" name="apellidos" class="form-control mb-4" required />
                                <p class="text-left">Fecha de nacimiento</p>
                                <input type="date" id="registroFechaNac" name="fechaNacimiento" class="form-control mb-4" required />
                                <p class="text-left">Email</p>
                                <input type="email" id="registroEmail" name="email" class="form-control mb-4" placeholder="ejemplo@gmail.com" required />
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
