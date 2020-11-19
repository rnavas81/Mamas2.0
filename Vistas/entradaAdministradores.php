<!DOCTYPE html>
<?php
/**
 * @author Rodrigo Navas
 * Pantalla de listados para administrador
 * Contiene los listados de administradores, profesores y usuarios.
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/GestionUsuarios.php';
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
$data = [];
//Comprueba la sesión para cargar datos de administradores,profesores o alumnos
$tipo = 'administradores';
if(isset($_SESSION['administradorTipo'])){
    $tipo = $_SESSION['administradorTipo'];
}
switch ($tipo){
    case 'administradores':
        $data = GestionUsuarios::getUsuarios(1);
        break;
    case 'profesores':
        $data = GestionUsuarios::getUsuarios(2);
        break;
    case 'alumnos':
        $data = GestionUsuarios::getUsuarios(3);
        break;
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
    <body>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12"><?=$msg?></span>
                </div>
                <div class="row">
                    <form action="<?=CTRL_ADMIN?>" method="POST">
                    </form>
                    <div class="btn-toolbar justify-content-between col-12" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group " role="group" aria-label="Botones izquierda">
                        </div>
                        <div class="btn-group" role="group" aria-label="Botones derecha">
                            <button name="agregarUsuarioFormulario" type="button" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>                
                <div class="container-fluid">                    
                    <!--Table-->
                    <table id="usuarios" class="table table-hover table-sm col-12">
                    <!--Table head-->
                      <thead>
                          <tr class="row">
                            <th class="col-2">DNI</th>
                            <th class="col-3">Nombre</th>
                            <th class="col-5">Apellidos</th>
                            <th class="col-2">Opciones</th>
                        </tr>
                      </thead>
                      <!--Table head-->
                      <!--Table body-->
                      <tbody>
                        <?php 
                        foreach ($data as $value) {?>
                        <tr class="row">
                          <th class="col-2" scope="row"><?=$value->getDni()?></th>
                          <td class="col-3"><?=$value->getNombre()?></td>
                          <td class="col-5"><?=$value->getApellidos()?></td>
                          <td class="col-2">
                            <form action="<?=CTRL_ADMIN?>" method="POST">
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />
                                <button name="editarUsuarioFormulario" type="submit" class="btn btn-primary btn-dark-green">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button name="eliminarUsuario" type="submit" class="btn btn-primary btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>  
                            </form>
                          </td>                          
                        </tr>
                        <?php } ?>
                      </tbody>
                      <!--Table body-->
                    </table>
                    <!--Table-->
                </div>
            </div>
        </main>
    </body>
</html>
