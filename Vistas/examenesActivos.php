<!DOCTYPE html>
<?php
/**
 * @author Darío León
 * Pantalla con los examenes activos del profesor donde podrá acceder a la correción
 * 
 */
require_once '../configuracion.php';
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
$usuario = $_SESSION['usuario'];
$data = [];

$tipo = 'activos';
if(isset($_SESSION['profesorTipo'])){
    $tipo = $_SESSION['profesorTipo'];
}
$tituloTabla = 'Exámenes';
$data = GestionExamenes::getExamenesByProfesor($_SESSION['usuario']->getId());
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
                        <span class="col text-center h3 primary-dark-color-text"><?=$msg?></span>                        
                    </div>
                </div>                
                <div class="container-fluid">                    
                    <!--Table-->
                    <table id="usuarios" class="table table-hover table-sm col-12">
                    <!--Table head-->
                    <thead>
                        <tr class="row">                            
                            <th class="col-sm-6 col-lg-7 text-center font-weight-bold">Nombre</th>                            
                            <th class="col-sm-2 text-center font-weight-bold">Fecha Inicio</th>
                            <th class="col-sm-2 text-center font-weight-bold">Fecha Fin</th>
                            <th class="col-sm-2 col-lg-1 text-center font-weight-bold">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data as $value) {
                            if($value->getIdProfesor()===$_SESSION['usuario']->getId()){                          
                                if($value->getActivo()==1) {
                        ?>
                        <tr class="row <?=$value->getActivo()==0?'desactivado':''?>">
                            <th class="col-sm-6 col-lg-7 text-uppercase" scope="row"><?=$value->getNombre()?></th>                          
                            <td class="col-sm-2 text-center"><?=$value->getFechaInicio()?></td>
                            <td class="col-sm-2 text-center"><?=$value->getFechaFin()?></td>
                            <td class="col-sm-2 col-lg-1">
                                <form class="d-flex justify-content-end" action="<?=CTRL_PROFESORES?>" method="POST">
                                    <input type="hidden" value="<?=$value->getId()?>" name="id" />                                                                
                                    <button name="verExamen" type="submit" class="btn btn-sm btn-primary btn-opcion px-2 mx-1" title="Ver">
                                        <i class="far fa-eye"></i>
                                    </button>                                
                                </form>
                            </td>                          
                        </tr>                        
                        <?php }}} ?>
                    </tbody>                        
                    </table>                    
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