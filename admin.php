<!DOCTYPE html>
<?php
require_once './configuracion.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}

//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}
?>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Mamas 2.0</title>
        <!-- Icono -->
        <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
        <!-- Google Fonts Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <!-- mdBootstrap css -->
        <link rel="stylesheet" href="css/mdb.min.css" />
        <!-- Estilos propios -->
        <link rel="stylesheet" href="css/style.css" /> 
    </head>
    <body>
        <main class="">
            <div class="col-12 d-flex justify-content-end mr-4">
                <a href="<?=WEB_INDEX?>">Acceso para alumnos/profesores</a>
            </div>
            <div class="container-fluid py-5">
                <div class="row">
                    <span class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-md-12 col-lg-6 pb-6">
                        <div class="container">                            
                            <form class="text-center border border-light p-5" action="<?=CTRL_BASICO?>" method="POST">
                                <p class="h4 mb-4">Inicio administrador</p>
                                <input type="text" id="loginDniAl" name="dni" class="form-control mb-4" placeholder="DNI" required />
                                <input type="password" id="loginPassAl" name="password" class="form-control mb-4" placeholder="Password" required/>
                                <!--                                
                                <div class="d-flex justify-content-around">
                                    <a href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                                </div>
                                -->
                                <div>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="accederAdminstradores">Acceder</button>
                                </div>
                            </form>                            
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>
        </main>
    </body>
</html>
