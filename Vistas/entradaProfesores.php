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
        <link rel="icon" href="../img/mdb-favicon.ico" type="image/x-icon" />
        <?php  //Google Fonts Roboto ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
        <?php //Font Awesome ?>
        <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
        <?php  //Bootstrap core ?>
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <?php //mdBootstrap css ?>
        <link rel="stylesheet" href="../css/mdb.min.css" />
        <!-- Para la cabecera -->
        <link rel="stylesheet" href="../css/sidebar.css" />
        <?php //Estilos propios ?>
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
                            <form action="<?=CTRL_EXAMENES?>" method="POST">
                                <button name="nuevo" type="submit" class="btn btn-primary btn-sm" title="Nuevo examen">
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
                            if($value->getIdProfesor()===$_SESSION['usuario']->getId()){  
                        ?>
                          <tr class="row <?=$value->getActivo()==0?'desactivado':''?>">
                          <th class="col-sm-6 text-uppercase" scope="row"><?=$value->getNombre()?></th>                          
                          <td class="col-sm-2 text-center"><?=$value->getFechaInicio()?></td>
                          <td class="col-sm-2 text-center"><?=$value->getFechaFin()?></td>
                          <td class="col-sm-2">
                              <form class="d-flex justify-content-end" action="<?=CTRL_PROFESORES?>" method="POST">
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />                                
                                <?php 
                                if($value->getActivo()==0) {
                                ?>
                                <button name="activarExamen" type="submit" class="btn btn-sm btn-blue-grey mx-1 my-0" title="Activar">
                                    <i class="far fa-square"></i>
                                </button>
                                <?php
                                } else {
                                ?>
                                <button name="desactivarExamen" type="submit" class="btn btn-sm btn-blue-grey mx-1 my-0" title="Desactivar">
                                    <i class="fas fa-check-square"></i>
                                </button>
                                <?php                                
                                }
                                ?>
                                <button name="editarExamen" type="submit" class="btn btn-sm btn-dark-green mx-1 my-0" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>                                
                                <button type="button" class="btn btn-sm btn-danger mx-1 my-0" data-toggle="modal" data-target="#modalEliminar" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRMAR ELIMINACION</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <div class="modal-body text-center">
                                            <p>Va a eliminar un examen</p> 
                                            ¿Desea continuar?
                                        </div>
                                        <div class="text-center pb-2">
                                            <button name="eliminarExamen" type="submit" class="btn btn-danger" title="Eliminar">
                                                CONTINUAR
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>                                             
                                        </div>
                                        </div>
                                    </div>
                                </div>
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
        <script src="../js/jquery/jquery.min.js"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
        <!-- Your custom scripts (optional) -->
        <script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
<?php //Bootstrap tooltips ?>
        <script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
<?php //Bootstrap core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/bootstrap.min.js"></script>
<?php //MDB core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/mdb.min.js"></script>
<?php //jQuery Custom Scroller CDN ?>
        <script type="text/javascript" src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
<?php //Your custom scripts (optional) ?>
        <script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
    </body>
</html>