<?php
/**
 * @author Rodrigo Navas
 */
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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Mamas 2.0</title>
        <!-- Icono -->
        <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/fontawesome/css/all.min.css" />
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <!-- mdBootstrap css -->
        <link rel="stylesheet" href="css/mdb.min.css" />
        <!-- Estilos propios -->
        <link rel="stylesheet" href="css/style.css" /> 
    </head>
    <body class="fondo-pantalla">
        <main class="vw-100 vh-100">            
            <div class="login-top">
                <span class="text-center msg-info d-block h3-responsive"><?=$msg?></span>
            </div>
            <div class="container-fluid login-center d-flex align-items-center justify-content-center">
                <div class="col-md-12 col-lg-6 pb-6">
                    <div class="container">                            
                        <form class="text-center border border-light p-5 white" action="<?=CTRL_BASICO?>" method="POST">
                            <p class="h4 mb-4">Inicio de sesión</p>
                            <input type="text" id="dni" name="dni" class="form-control mb-4" placeholder="DNI" required />
                            <input type="password" id="password" name="password" class="form-control mb-4" placeholder="Password" required/>                  
                            <p>
                            ¿No estás registrado?
                            <a class="primary-dark-color-text" href="<?=WEB_REGISTRAR?>">Registrarse</a>
                            </p>
                            <p>
                            ¿Has olvidado tu contraseña?
                            <a class="primary-dark-color-text" href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                            </p>
                            <div>
                                <button class="btn primary-color white-text btn-block my-4" type="submit" name="acceder">Acceder</button>
                            </div>
                        </form>                            
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
