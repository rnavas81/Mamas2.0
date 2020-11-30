<!DOCTYPE html>
<?php
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
//Comprueba la sesión para cargar datos de administradores,profesores o alumnos
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
                <div class="row">
                    <div class="btn-toolbar justify-content-between col-12" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="align-items-center btn-group" role="group" aria-label="Botones izquierda">
                            <span class="align-self-center h3 mb-0"><?=$tituloTabla?></span>
                        </div>
                        <span class="col text-center h3 primary-dark-color-text"><?=$msg?></span>
                        <div class="btn-group" role="group" aria-label="Botones derecha">
                            <form action="<?=CTRL_EXAMENES?>" method="POST">
                                <button name="nuevapregunta" type="submit" class="btn btn-primary btn-sm" title="Nueva Pregunta">
                                    <i class="fas fa-question"></i>
                                </button>
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
                                $asignar = true;
                                if($value->getFechaInicio()!=null){
                                     $fechaInicio = new DateTime($value->getFechaInicio());
                                     $now = new DateTime();
                                     $asignar = $fechaInicio>$now;
                                }
                        ?>
                          <tr class="row <?=$value->getActivo()==0?'desactivado':''?>">
                          <th class="col-sm-6 text-uppercase" scope="row"><?=$value->getNombre()?></th>                          
                          <td class="col-sm-2 text-center"><?=$value->getFechaInicio()?></td>
                          <td class="col-sm-2 text-center"><?=$value->getFechaFin()?></td>
                          <td class="col-sm-2">
                              <form class="d-flex justify-content-end" action="<?=CTRL_PROFESORES?>" method="POST">
                                <input type="hidden" value="<?=$value->getId()?>" name="id" />                                
                                <?php 
                                if($asignar){
                                ?>
                                <button name="asignarExamen" type="submit" class="btn mx-1 btn-blue btn-opcion px-2" title="Asignar">
                                    <i class="fas fa-tasks"></i>
                                </button>  
                                <?php }
                                if($value->getActivo()==0) {
                                ?>
                                <button name="activarExamen" type="submit" class="btn mx-1 btn-blue-grey btn-opcion px-2" title="Activar">
                                    <i class="far fa-square"></i>
                                </button>
                                <?php
                                } else {
                                ?>
                                <button name="desactivarExamen" type="submit" class="btn mx-1 btn-blue-grey  btn-opcion px-2" title="Desactivar">
                                    <i class="fas fa-check-square"></i>
                                </button>
                                <?php                                
                                }?>
                                <button name="editarExamen" type="submit" class="btn mx-1 btn-dark-green btn-opcion px-2" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>                                
                                <button type="button" class="btn mx-1 btn-danger btn-opcion px-2" data-toggle="modal" data-target="#modalEliminar" title="Eliminar">
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
    </body>
</html>