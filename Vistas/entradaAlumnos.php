<!DOCTYPE html>
<?php
require_once '../configuracion.php';
require_once '../Modelos/GestionExamenes.php';
require_once '../Modelos/Usuario.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
isset($_SESSION['usuario']) OR header("Location ".CTRL_BASICO);

//Comprueba que hay un usuario logueado y tiene permisos de alumno
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(3)) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if(isset($_SESSION['MSG_INFO'])){
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}

$data = [];
//Comprueba la sesión para cargar datos de administradores,profesores o alumnos
$tipo = 'activos';
if(isset($_SESSION['alumnoTipo'])){
    $tipo = $_SESSION['alumnoTipo'];
}
$tipoOpciones="alumnosDashboard";
switch ($tipo){
    case 'activos':
        $data = GestionExamenes::getExamenAlumno(1,$_SESSION['usuario']->getId());
        $tituloTabla="Examenes pendientes";
        break;
    case 'desactivados':
        $data = GestionExamenes::getExamenAlumno(0,$_SESSION['usuario']->getId());
        $tituloTabla="Examenes realizados";
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
        <!-- Para la cabecera -->
        <link rel="stylesheet" href="../css/sidebar.css" />
        <!-- Estilos propios -->
        <link rel="stylesheet" href="../css/style.css" /> 
        
    </head>
    <body>
        <?php
        require_once '../Componentes/cabecera.php';
        ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <span class="col-12"><?=$msg?></span>
                </div>
                <div class="row">
                    <div class="btn-toolbar justify-content-between col-12" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="align-items-center btn-group" role="group" aria-label="Botones izquierda">
                            <span class="align-self-center h3 mb-0"><?=$tituloTabla?></span>
                        </div>
                        <div class="btn-group" role="group" aria-label="Botones derecha">
                            <form action="<?=CTRL_ALUMNOS?>" method="POST">
                                <button name="examenesPendientes" type="submit" class="btn btn-primary btn-sm">
                                    Pendientes
                                </button>
                                <button name="examenesRealizados" type="submit" class="btn btn-primary btn-sm">
                                    Realizados
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
                                <th class="col-sm-6 text-center font-weight-bold">Nombre</th>                            
                                <th class="col-sm-2 text-center font-weight-bold">Fecha Inicio</th>
                                <th class="col-sm-2 text-center font-weight-bold">Fecha Fin</th>
                                <th class="col-sm-2 text-center font-weight-bold">Opciones</th>
                            </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>
                        <?php 
                        foreach ($data as $value) {                              
                        ?>
                        <tr class="row <?=$tipo=="desactivados"?'desactivado':''?>">
                            <th class="col-sm-6 text-uppercase" scope="row"><?=$value->getNombre()?></th>                          
                            <td class="col-sm-2 text-center"><?=$value->getFechaInicio()?></td>
                            <td class="col-sm-2 text-center"><?=$value->getFechaFin()?></td>
                            <td class="col-sm-2">
                                <form class="d-flex justify-content-end" action="<?=CTRL_EXAMENES?>" method="POST">
                                    <input type="hidden" value="<?=$value->getId()?>" name="id" />
                                    <?php 
                                    if($tipo=="activos"){
                                    ?>
                                    <button name="empezarExamen" type="submit" class="btn btn-sm btn-dark-green mx-1 my-0" title="Empezar">
                                        <i class="fas fa-play-circle"></i>
                                    </button> 
                                    <?php 
                                    } else {
                                    ?>
                                    <!--<button name="editarExamen" type="submit" class="btn btn-sm btn-dark-green mx-1 my-0" title="Empezar">
                                        <i class="fas fa-play-circle"></i>
                                    </button> -->
                                    <?php 
                                    }
                                    ?>
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