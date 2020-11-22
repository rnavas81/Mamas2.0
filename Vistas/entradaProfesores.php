<?php
require_once '../configuracion.php';
require_once '../Modelos/GestionExamenes.php';
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
$usuario = $_SESSION['usuario'];
$data = [];
//Comprueba la sesión para cargar datos de administradores,profesores o alumnos
$tipo = 'activos';
if(isset($_SESSION['profesorTipo'])){
    $tipo = $_SESSION['profesorTipo'];
}
$tipoOpciones="profesorDashboard";
/*
switch ($tipo){
    case 'activos':
        $data = GestionExamenes::getExamen($_SESSION['usuario']->getId());
        $tituloTabla="Activos";
        break;
    case 'desactivados':
        $data = GestionExamenes::getExamen($_SESSION['usuario']->getId());
        $tituloTabla="Desactivados";
        break;    
}*/
$tituloTabla = 'Exámenes';
$data = GestionExamenes::getExamen($_SESSION['usuario']->getId());
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
                            <form action="" method="POST">
                                <button name="agregarExamenFormulario" type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i>
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
                            <th class="col-sm-4 text-center font-weight-bold">Nombre</th>                            
                            <th class="col-sm-2 text-center font-weight-bold">Fecha Inicio</th>
                            <th class="col-sm-2 text-center font-weight-bold">Fecha Fin</th>
                            <th class="col-sm-4 text-center font-weight-bold">Opciones</th>
                        </tr>
                      </thead>
                      <!--Table head-->
                      <!--Table body-->
                      <tbody>
                        <?php 
                        foreach ($data as $value) {
                            if($value->getIdProfesor()===$_SESSION['usuario']->getId()){  
                        ?>
                          <tr class="row <?=$value->getActivo()==0?'desactivado':''?>">
                          <th class="col-sm-4 text-center text-uppercase" scope="row"><?=$value->getNombre()?></th>                          
                          <td class="col-sm-2 text-center"><?=$value->getFechaInicio()?></td>
                          <td class="col-sm-2 text-center"><?=$value->getFechaFin()?></td>
                          <td class="col-sm-4">
                              <form class="d-flex justify-content-center" action="<?=CTRL_PROFESORES?>" method="POST">
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />
                                <input type="hidden" value="<?=$value->getActivo()?>" name="activo"/>
                                <?php 
                                if($value->getActivo()==0) {
                                ?>
                                <button name="activarExamen" type="submit" class="btn btn-sm btn-dark-green mx-1 my-0" title="Activar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php
                                } else {
                                ?>
                                <button name="desactivarExamen" type="submit" class="btn btn-sm btn-danger mx-1 my-0" title="Desactivar">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                                <?php                                
                                }
                                ?>
                                <button name="editarExamen" type="submit" class="btn btn-sm btn-dark-green mx-1 my-0" title="Activar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button name="eliminarExamen" type="submit" class="btn btn-sm btn-danger mx-1 my-0" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>  
                            </form>
                          </td>                          
                        </tr>
                        <?php }} ?>
                      </tbody>
                      <!--Table body-->
                    </table>
                    <!--Table-->
                </div>
            </div>
        </main>        
    <script src="../js/jquery.min.js"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
    <!-- Your custom scripts (optional) -->
    <script type="text/javascript" src="../js/sidebar.js"></script>
    </body>
</html>