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
                <div class="col-12 d-flex justify-content-end mr-4">
                    <a class="primary-dark-color-text white" href="<?=WEB_ADMIN?>">Acceso para administradores</a>
                </div>
                <span class="text-center msg-info d-block h3-responsive"><?=$msg?></span>
            </div>
            <div class="container-fluid login-center d-flex flex-wrap align-items-center">
                <div class="col-lg-1"></div>
                <div class="col-md-12 col-lg-5 pb-5">
                    <div class="container">                            
                        <form class="text-center border border-light p-5 white" action="<?=CTRL_BASICO?>" method="POST">
                            <p class="h4 mb-4">Inicio alumnos</p>
                            <input type="text" id="loginDniAl" name="dni" class="form-control mb-4" placeholder="DNI" required />
                            <input type="password" id="loginPassAl" name="password" class="form-control mb-4" placeholder="Password" required/>
<!--                                <div class="d-flex justify-content-around">
                                <a href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                            </div>-->
                            <div>
                                <button class="btn primary-color white-text btn-block my-4" type="submit" name="accederAlumnos">Acceder</button>
                            </div>                    
                            <p>
                            ¿No estás registrado?
                            <a class="primary-dark-color-text" href="<?=WEB_REGISTRAR?>?tipo=alumno">Registrarse</a>
                            </p>                  
                            <p>
                            ¿Has olvidado tu contraseña?
                            <a class="primary-dark-color-text" href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                            </p>
                        </form>                            
                    </div>
                </div>

                <div class="col-md-12 col-lg-5 pb-5">
                    <div class="container">
                        <form class="text-center border border-light p-5 white" action="<?=CTRL_BASICO?>" method="POST">
                            <p class="h4 mb-4">Inicio profesores</p>
                            <input type="text" id="loginDniAl" name="dni" class="form-control mb-4" placeholder="DNI" required />
                            <input type="password" id="loginPassAl" name="password" class="form-control mb-4" placeholder="Password" required/>
<!--                                <div class="d-flex justify-content-around">
                                <a href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                            </div>-->
                            <div>
                                <button class="btn primary-color white-text btn-block my-4" type="submit" name="accederProfesores">Acceder</button>
                            </div>                    
                            <p>
                            ¿No estás registrado?
                            <a class="primary-dark-color-text" href="<?=WEB_REGISTRAR?>?tipo=profesor">Registrarse</a>
                            </p>                
                            <p>
                            ¿Has olvidado tu contraseña?
                            <a class="primary-dark-color-text" href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                            </p>
                        </form>                            
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </main>
    </body>
</html>
