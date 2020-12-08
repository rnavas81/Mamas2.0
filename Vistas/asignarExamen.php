<?php
/**
 * @author Rodrigo Navas
 * Pantalla de listados para administrador
 * Contiene los listados de administradores, profesores y usuarios.
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';
require_once '../Modelos/GestionExamenes.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
//Comprueba que hay un usuario logueado y tiene permisos de administrador
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(1) ||$_SESSION['usuario']->hasRol(2)) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}
$examen = $_SESSION['datosExamen'];
//Comprueba la sesión para cargar datos de administradores,profesores o alumnos
$response = GestionExamenes::getAsignacionAlumons($examen['id']);
if($response){
    $data = json_decode($response,true);
} else {
    $data = [];
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
        <!-- Para la cabecera -->
        <link rel="stylesheet" href="../css/sidebar.css" />
        <!-- Estilos propios -->
        <link rel="stylesheet" href="../css/style.css" /> 
        
    </head>
    <body>
        <?php
        $tipoOpciones="profesorDashboard";
        require_once '../Componentes/cabecera.php';
        ?>
        <main>
            <div class="container-fluid">
                <div class="row btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="align-items-center btn-group" role="group" aria-label="Botones izquierda">
                        <span class="align-self-center h3 mb-0">Asignar examen</span>
                    </div>
                    <div class="btn-group">
                        <span class="align-self-center"><?=$msg?></span>
                    </div>
                    <div class="btn-group" role="group" aria-label="Botones derecha">
                        <form id="formToolbar" action="<?=CTRL_EXAMENES?>" method="POST">
                            <input type="hidden" name="idsUsuario" />
                            <input type="hidden" value="<?=$examen['id']?>" name="idExamen" />
                            <button name="volver" type="submit" class="btn primary-color btn-sm" title="Volver">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            <button name="asignarTodos" type="submit" class="btn btn-primary btn-sm" title="Asignar a todos">
                                <i class="fas fa-check-double"></i>
                            </button>
                        </form>
                    </div>
                </div>        
                <div class="d-flex px-1">                    
                    <!--Table-->
                    <table id="alumnos" class="table table-hover table-sm col-12">
                    <!--Table head-->
                      <thead>
                          <tr class="row">
                            <th class="col-sm-2 text-center font-weight-bold">
                                <input class="form-control" type="search" id="filter_dni" value=""/>
                            </th>
                            <th class="col-sm-3 text-center font-weight-bold">
                                <input class="form-control" type="search" id="filter_nombre" value=""/>
                            </th>
                            <th class="col-sm-6 text-center font-weight-bold">
                                <input class="form-control" type="search" id="filter_apellidos" value=""/>
                            </th>
                            <th class="col-sm-1 text-center font-weight-bold">
                            </th>
                        </tr>
                      </thead>
                      <!--Table head-->
                      <!--Table body-->
                      <tbody>
                        <?php 
                        foreach ($data as $value) {?>
                        <tr class="row" name="fila">
                          <input type="hidden" value="<?=$value['id']?>" name="id" />
                          <th class="col-sm-2 text-uppercase" scope="row" name="dni"><?=$value['dni']?></th>
                          <td class="col-sm-3" name="nombre"><?=$value['nombre']?></td>
                          <td class="col-sm-6" name="apellidos"><?=$value['apellidos']?></td>
                          <td class="col-sm-1">
                              <form name="formUsuario" class="d-flex justify-content-end" action="<?=CTRL_EXAMENES?>" method="POST">
                                <input type="hidden" value="<?=$value['id']?>" name="idUsuario" />
                                <input type="hidden" value="<?=$examen['id']?>" name="idExamen" />
                                <button name="<?=$value['asignado']==0?'asignar':'desasignar'?>" type="submit" class="btn btn-sm primary-color white-text mx-1 my-0" title="<?=$value['asignado']==0?'Asignar':'Desasignar'?>">
                                    <i class="far fa-<?=$value['asignado']==0?'square':'check-square'?>"></i>
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
        <script src="../js/jquery/jquery.min.js"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
        <!-- Your custom scripts (optional) -->
        <script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
        <script type="text/javascript" src="../js/asignarExamen.js"></script>
    </body>
</html>
