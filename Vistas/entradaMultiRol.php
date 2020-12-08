<?php
/**
 * @author Rodrigo Navas
 * Formulario para redirigir el acceso de un usuario con varios roles.
 * 
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
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}
//Recupera el usuario
$usuario = $_SESSION['usuario'];
//Recupera los roles
$roles = json_decode(GestionUsuarios::getRoles(),true);
$options=[];
foreach ($roles as $rol) {
    if($usuario->hasRol($rol['id'])){
        $options[]=$rol;
    } elseif($rol['id']==ROL_PROFESOR && $usuario->hasRol(ROL_ADMINISTRADOR)){
        $options[]=$rol;
    } 
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
        <link rel="icon" href="../img/mdb-favicon.ico" type="image/x-icon" />
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
                            <p class="h4 mb-4">Tipo de acceso</p>
                            <select class="form-control" name="acceso">
                                <?php
                                foreach ($options as $option) {?>
                                <option value="<?=$option['id']?>"><?=$option['nombre']?></option>
                                <?php }?>
                            </select>
                            <div>
                                <button class="btn primary-color white-text btn-block my-4" type="submit" name="accederMulti">Acceder</button>
                                <button class="btn primary-color white-text btn-block my-4" type="submit" name="salir">Salir</button>
                            </div>
                        </form>                            
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
