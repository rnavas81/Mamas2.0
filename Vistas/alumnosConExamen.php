<!DOCTYPE html>
<?php
/**
 * @author Darío León
 * Pantalla de listados de alumnos con un examen
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/GestionUsuarios.php';
require_once '../Modelos/GestionExamenes.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
//Comprueba que hay un usuario logueado y tiene permisos de administrador o profesor
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(1) || $_SESSION['usuario']->hasRol(2)) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}


if(isset($_SESSION['profesorTipo'])){
    $tipo = $_SESSION['profesorTipo'];
}

$tituloTabla="Alumnos";

$alumnos = GestionExamenes::getAlumnosExamen($_SESSION['usuario']->getId(), $_SESSION['examenAct']['id']);

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
                <div class="row">
                    <div class="btn-toolbar justify-content-between col-12" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="align-items-center btn-group" role="group" aria-label="Botones izquierda">
                            <span class="align-self-center h3 mb-0"><?=$tituloTabla?></span>
                        </div>
                        <div class="btn-group">
                            <span class="align-self-center"><?=$msg?></span>
                        </div>
                        <div class="btn-group" role="group" aria-label="Botones derecha">
                            <form>                                
                                <button name="volver" type="button" class="btn btn-primary btn-sm" onclick="history.go(-1);" title="Volver">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </form>
                        </div>
                    </div>                    
                </div>                
                <div class="container-fluid">                    
                    <!--Table-->
                    <table id="usuarios" class="table table-hover table-sm col-12">
                    <!--Table head-->
                      <thead>
                          <tr class="row">
                            <th class="col-sm-2 text-center font-weight-bold">DNI</th>
                            <th class="col-sm-3 text-center font-weight-bold">Nombre</th>
                            <th class="col-sm-5 col-lg-6 text-center font-weight-bold">Apellidos</th>
                            <th class="col-sm-2 col-lg-1 text-center font-weight-bold">Opciones</th>
                        </tr>
                      </thead>
                      <!--Table head-->
                      <!--Table body-->
                      <tbody>
                        <?php 
                        foreach ($alumnos as $value) {
                            ?>
                        <tr class="row">
                          <th class="col-sm-2 text-uppercase" scope="row"><?=$value->getDni()?></th>
                          <td class="col-sm-3"><?=$value->getNombre()?></td>
                          <td class="col-sm-5 col-lg-6"><?=$value->getApellidos()?></td>
                          <td class="col-sm-2 col-lg-1">
                              <form class="d-flex justify-content-end" action="<?=CTRL_PROFESORES?>" method="POST">
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />                                
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />
                                <button name="corregirExamen" type="submit" class="btn btn-sm btn-dark-green btn-opcion px-2 mx-1" title="Corregir">
                                    <i class="fas fa-pencil-alt"></i>
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
    </body>
</html>
