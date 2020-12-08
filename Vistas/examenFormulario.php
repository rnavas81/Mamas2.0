<?php
/**
 * @author Rodrigo Navas
 * 
 * Formulario de generar un examen
 * Este formulario sirve para leer/crear/modificar un examen
 * 
 * La funcionalidad del formulario se determina en la variable de sesión accesoFormulario.
 * Por defecto la funcionalidad será leer.
 * 
 * Los datos del formulario se determinan en la variable de sesión datosFormulario
 * Por defecto los datos estarán vacíos.
 */
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';
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
   $msg= $_SESSION['MSG_INFO'];
   unset($_SESSION['MSG_INFO']);
}
//Recupera los datos del formulario
$datos = [
    "id"=>0,
    "nombre"=>"",
    "descripcion"=>"",
    "activo"=>1,
    "fechaInicio"=>null,
    "fechaFin"=>null,
    "preguntas"=>[]
];
if(isset($_SESSION['datosFormulario'])){
    $datos = $_SESSION['datosFormulario'];
    if($datos['fechaInicio']!=null){
       $fechaInicio=new DateTime($datos['fechaInicio']);
        $datos['fechaInicio']=$fechaInicio->format("Y-m-d\Th:i");
    }
    if($datos['fechaFin']!=null){
       $fechaFin=new DateTime($datos['fechaFin']);
        $datos['fechaFin']=$fechaFin->format("Y-m-d\Th:i");
    }
            
}
$accesoFormulario = 'leer';
if(isset($_SESSION['accesoFormulario'])){
    $accesoFormulario = $_SESSION['accesoFormulario'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Mamas 2.0</title>
        <?php  //Icono ?>
        <link rel="icon" href="../img/mdb-favicon.ico" type="image/x-icon" />
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
    <body class="fondo-pantalla">
        <?php
        $tipoOpciones="profesorDashboard";
        require_once '../Componentes/cabecera.php';
        ?>
        <main class="">
            <div class="container-fluid">
                <div class="d-flex">
                    <span id="msg" class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-lg-10 col-md-10 col-sm-10 py-3 white border">
                        <div class="container">
                            <form class="text-center white" id="formExamen" name="formExamen" action="<?=CTRL_EXAMENES?>" method="POST" novalidate>
                                <input type="hidden" id="id" name="id" value="<?=$datos['id']?>"/>
                                <input type="hidden" id="datos" name="datos" value="<?=json_encode($datos)?>"/>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="nombre" placeholder="Nombre" required value="<?=$datos['nombre']?>"/>
                                </div>
                                <div class="form-group">
                                    <textarea rows="2" class="form-control" type="text" name="descripcion" placeholder="Descripción"><?=$datos['descripcion']?></textarea>                                    
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12 col-md-6 col-lg-5 d-flex">
                                        <label class="mx-2 w-25">Inicio</label>
                                        <input class="form-control w-auto" type="datetime-local" name="fechaInicio" value="<?=$datos['fechaInicio']?>"/>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-5 d-flex">
                                        <label class="mx-2 w-25">Fin</label>
                                        <input class="form-control w-auto" type="datetime-local" name="fechaFin" value="<?=$datos['fechaFin']?>"/>
                                    </div>
                                    <div class="form-check form-check-inline d-flex justify-content-md-end col-sm-12 col-md-6 col-lg align-items-baseline">
                                        <input class="form-check-input" type="checkbox" name="activo" checked="<?=$datos['activo']==1?'true':'false'?>">
                                        <label class="form-check-label" for="activo">Activo</label>
                                    </div>                                   
                                </div>
                                <hr/>
                                <div class="">
                                    <ul class="list-group list-group-flush" id="lista-preguntas"></ul>
                                </div>
                                <div class="d-flex justify-content-center py-2">
                                    <button id="agregar" type="button" class="btn btn-dark-green" title="Agregar pregunta">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button id="buscar" type="button" class="btn btn-blue" title="Buscar pregunta"
                                            data-toggle="modal" data-target="#modalPreguntas">
                                        <i class="fas fa-binoculars"></i>
                                    </button>
                                </div>
                                <hr/>                                
                            <?php
                                //******* BOTONES
                                switch ($accesoFormulario) {
                                        case "leer":?>
                                <div class="d-flex justify-content-end">
                                    <button class="btn secondary-color primary-dark-color-text" type="submit" name="volver">
                                       Volver
                                    </button>
                                <?php 
                                        break;
                                        case "crear":?>
                                <div class="d-flex justify-content-center">
                                    <button class="btn primary-color" type="submit" name="crear">
                                       Crear
                                    </button>
                                    <button class="btn secondary-color primary-dark-color-text" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción para leer será volver
                                        case "modificar":?>
                                <div class="d-flex justify-content-center">
                                    <button class="btn primary-color" type="submit" name="modificar">
                                       Modificar
                                    </button>
                                    <button class="btn secondary-color primary-dark-color-text" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción por defecto será volver.
                                        default:?>
                                <div class="d-flex justify-content-end">
                                    <button class="btn secondary-color primary-dark-color-text" type="submit" name="volver">
                                       Volver
                                    </button>
                                    <?php 
                                        break;
                                    }?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>                        
            <?php
            require_once '../Componentes/modalPreguntas.php';
            ?>
        </main>
    </body>
    <script  type="text/javascript">
        var data = <?php
        if(count($datos['preguntas'])>0){
            echo json_encode($datos['preguntas']);
        } else {
            echo json_encode([]);
        }
        ?>; 
    </script>
<?php //jQuery ?>
    <script type="text/javascript" src="../js/jquery/jquery.min.js"></script>
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
    <script type="text/javascript" src="../js/Pregunta.js"></script>
    <script type="text/javascript" src="../js/examenFormulario.js"></script>
    <script type="text/javascript" src="../js/varios.js"></script>
</html>
