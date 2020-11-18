<!DOCTYPE html>
<?php
require_once './configuracion.php';
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    
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
        <main class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-md-12 col-lg-5 pb-5">
                        <div class="container">                            
                            <form class="text-center border border-light p-5" action="<?=CTRL_BASICO?>" method="POST">
                                <p class="h4 mb-4">Inicio alumnos</p>
                                <input type="text" id="loginDniAl" name="dni" class="form-control mb-4" placeholder="DNI" required />
                                <input type="password" id="loginPassAl" name="password" class="form-control mb-4" placeholder="Password" required/>
<!--                                <div class="d-flex justify-content-around">
                                    <a href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                                </div>-->
                                <div>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="accederAlumnos">Sign in</button>
                                </div>                    
                                <p>
                                ¿No estás registrado?
                                <a href="<?=WEB_REGISTRAR?>">Registrarse</a>
                                </p>
                            </form>                            
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-5 pb-5">
                        <div class="container">
                            <form class="text-center border border-light p-5" action="<?=CTRL_BASICO?>" method="POST">
                                <p class="h4 mb-4">Inicio profesores</p>
                                <input type="text" id="loginDniAl" name="dni" class="form-control mb-4" placeholder="DNI" required />
                                <input type="password" id="loginPassAl" name="password" class="form-control mb-4" placeholder="Password" required/>
<!--                                <div class="d-flex justify-content-around">
                                    <a href="<?=WEB_RECUPERAR?>">Recuperar contraseña</a>
                                </div>-->
                                <div>
                                    <button class="btn btn-info btn-block my-4" type="submit" name="accederProfesores">Sign in</button>
                                </div>                    
                                <p>
                                ¿No estás registrado?
                                <a href="<?=WEB_REGISTRAR?>">Registrarse</a>
                                </p>
                            </form>                            
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
            </div>
        </main>
    </body>
</html>
