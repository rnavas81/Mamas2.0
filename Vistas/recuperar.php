<!DOCTYPE html>
<?php
require_once '../configuracion.php';
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
        <!-- Google Fonts Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <!-- mdBootstrap css -->
        <link rel="stylesheet" href="../css/mdb.min.css" />
        <!-- Estilos propios -->
        <link rel="stylesheet" href="../css/style.css" /> 
    </head>
    <body class="fondo-pantalla">
        <main class="vw-100 vh-100">
            <div class="login-top">
                <span class="col-12 text-center"><?=$msg?></span>
            </div>
            <div class="container-fluid login-center d-flex align-items-center justify-content-center">
                    <div class="col-md-12 col-lg-6 pb-6">
                        <div class="container">                            
                            <form class="text-center border border-light p-5 white" action="<?=CTRL_BASICO?>" method="POST">
                                <p class="h4 mb-4">Recuperar contraseña</p>
                                <input type="email" id="email" name="email" class="form-control mb-4" placeholder="email" required />
                               <div class="invalid-feedback" id="errorEmail" aria-live="polite"></div> <p>
                                <div>
                                    <button class="btn primary-color white-text btn-block my-4" type="submit" name="recuperarPass">Recuperar</button>
                                    <button class="btn primary-color white-text btn-block my-4" type="submit" name="volver">Volver</button>
                                </div>
                            </form>                            
                        </div>
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
        <!-- Validación propia -->
        <script type = "text/javascript" src="../js/recuperar.js"></script>  
    </body>
</html>
