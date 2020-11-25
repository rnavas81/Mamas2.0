<!DOCTYPE html>
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
}
$accesoFormulario = 'leer';
if(isset($_SESSION['accesoFormulario'])){
    $accesoFormulario = $_SESSION['accesoFormulario'];
}
?>
<html>
    <head>
        <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>Mamas 2.0</title>
        <?php  //Icono ?>
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
        $tipoOpciones="profesorDashboard";
        require_once '../Componentes/cabecera.php';
        ?>
        <main class="">
            <div class="container-fluid">
                <div class="d-flex">
                    <span id="msg" class="col-12 text-center"><?=$msg?></span>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-lg-10 col-md-10 col-sm-10 py-3">
                        <div class="container">
                            <form class="text-center" id="formExamen" name="formExamen" action="<?=CTRL_EXAMENES?>" method="POST" novalidate>
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
                                    <ul class="list-group list-group-flush" id="lista-preguntas">
                                        <?php
                                        if(count($datos['preguntas'])>0){
                                        foreach ($datos['preguntas'] as $index=>$pregunta) {?>
                                        <li class="list-group-item border p-2 mb-3" name="pregunta" id="<?=$index+1?>">
                                            <div class="d-flex mb-2">
                                                <p name="titulo" class="h5 col text-left">Pregunta <?=$index+1?></p>
                                                <p class="h6 align-self-center mr-2">Tipo</p>
                                                <select name="tipo">
                                                    <option value="1" <?=$pregunta['tipo']==1?'selected':''?>>A desarrollar</option>
                                                    <option value="2" <?=$pregunta['tipo']==2?'selected':''?>>Respuesta única</option>
                                                    <option value="3" <?=$pregunta['tipo']==3?'selected':''?>>Respuesta multiple</option>
                                                </select>
                                                <button type="button" class="btn btn-sm m-0 ml-2 btn-danger" name="eliminarPregunta" title="Eliminar pregunta">
                                                    <i class="fas fa-times"></i>
                                                </button>  
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" name="enunciado" type="text" placeholder="Enunciado"
                                                       value="<?=$pregunta['enunciado']?>"/>
                                            </div>
                                            <div class="form-row opciones  <?=$pregunta['tipo']==1?'d-none':''?>">
                                                <div class="form-group d-flex col-12 col-sm-6">
                                                    <input class="form-control" name="opcion_1" type="text" placeholder="Opción 1"
                                                           value="<?= isset($pregunta['opciones'][0])?$pregunta['opciones'][0]['texto']:""?>"/>
                                                    <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-<?=isset($pregunta['opciones'][0]) && $pregunta['opciones'][0]['correcta']?'success':'danger'?>" opcion="1">
                                                        <i class="fas fa-check"></i>
                                                    </button>                                                    
                                                </div>
                                                <div class="form-group d-flex col-12 col-sm-6">
                                                    <input class="form-control" name="opcion_2" type="text" placeholder="Opción 2"
                                                        value="<?=isset($pregunta['opciones'][1])?$pregunta['opciones'][1]['texto']:""?>"/>
                                                    <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-<?=isset($pregunta['opciones'][1]) && $pregunta['opciones'][1]['correcta']?'success':'danger'?>" opcion="2">
                                                        <i class="fas fa-times"></i>
                                                    </button>                                                    
                                                </div>
                                                <div class="form-group d-flex col-12 col-sm-6">
                                                    <input class="form-control" name="opcion_3" type="text" placeholder="Opción 3"
                                                        value="<?=isset($pregunta['opciones'][2])?$pregunta['opciones'][2]['texto']:""?>"/>
                                                    <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-<?=isset($pregunta['opciones'][2]) && $pregunta['opciones'][2]['correcta']?'success':'danger'?>" opcion="3">
                                                        <i class="fas fa-times"></i>
                                                    </button>                                                    
                                                </div>
                                                <div class="form-group d-flex col-12 col-sm-6">
                                                    <input class="form-control" name="opcion_4" type="text" placeholder="Opción 4"
                                                        value="<?=isset($pregunta['opciones'][3])?$pregunta['opciones'][3]['texto']:""?>"/>
                                                    <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-<?=isset($pregunta['opciones'][3]) && $pregunta['opciones'][3]['correcta']?'success':'danger'?>" opcion="4">
                                                        <i class="fas fa-times"></i>
                                                    </button>                                                    
                                                </div>
                                            </div>
                                        </li>
                                        <?php }
                                        }?>
                                    </ul>
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
                                    <button class="btn btn-info" type="submit" name="volver">
                                       Volver
                                    </button>
                                <?php 
                                        break;
                                        case "crear":?>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-dark-green  " type="submit" name="crear">
                                       Crear
                                    </button>
                                    <button class="btn btn-danger" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción para leer será volver
                                        case "modificar":?>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-dark-green" type="submit" name="modificar">
                                       Modificar
                                    </button>
                                    <button class="btn btn-danger" type="submit" name="volver">
                                       Cancelar
                                    </button>
                                    <?php 
                                        break;
                                        //La acción por defecto será volver.
                                        default:?>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-info" type="submit" name="volver">
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
    <script type="text/javascript" src="../js/examenFormulario.js"></script>
    <script type="text/javascript" src="../js/varios.js"></script>
</html>
