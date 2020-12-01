<!DOCTYPE html>
<?php
/**
 * @author Rodrigo Navas
 * 
 * Formulario de datos para Usuario
 * Este formulario sirve para crear/modificar/leer datos de un usuario
 * 
 * La funcionalidad del formulario se determina en la variable de sesión accesoFormulario.
 * Por defecto la funcionalidad será leer.
 * 
 * Los datos del formulario se determinan en la variable de sesión datosFormulario
 * Por defecto los datos estarán vacíos.
 */
require_once '../configuracion.php';
require_once '../Modelos/Usuario.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
//Comprueba que hay un usuario logueado y tiene permisos de administrador
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
    "enunciado"=>'',
    "tipo"=>1,
    "opciones"=> []
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
            <div class="d-flex mt-5">
                <div class="col-lg-2"></div>
                <div class="col-lg-8 white p-5 border">
                    <div class="d-flex mb-2">
                        <p name="titulo" class="h5 col text-left"><?=$datos['id']==0?'Nueva pregunta':'Editar pregunta'?></p>
                        <p class="h6 align-self-center mr-2">Tipo</p>
                        <select class="form-control w-auto" name="tipo">
                            <option value="1" <?=$datos['tipo']==1?'selected':''?> >A desarrollar</option>
                            <option value="2" <?=$datos['tipo']==2?'selected':''?> >Respuesta única</option>
                            <option value="3" <?=$datos['tipo']==3?'selected':''?> >Respuesta multiple</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="enunciado" type="text" placeholder="Enunciado" 
                               value="<?=$datos['enunciado']?>"
                               required maxlength="500" minlength="10"/>
                    </div>
                    <div class="form-row opciones <?=$datos['tipo']==1?'d-none':''?>">
                        <?php 
                        if(count($datos['opciones'])>0){
                            foreach ($datos['opciones'] as $index => $opcion) {?>
                            <div class="form-group d-flex col-12 col-sm-6">
                                <input class="form-control" name="opcion_<?=$index+1?>" type="text" 
                                       placeholder="Opción <?=$index+1?>" value="<?=$opcion['texto']?>"/>
                                <button type="button" class="opcion btn btn-opcion m-0 <?=$opcion['correcta']?'btn-success':'btn-danger'?>" opcion="<?=$index+1?>">
                                    <i class="fas fa-<?=$opcion['correcta']?'check':'times'?>"></i>
                                </button>                                                    
                            </div>
                            <?php }
                        } else {?>
                        <div class="form-group d-flex col-12 col-sm-6">
                            <input class="form-control" name="opcion_1" type="text" placeholder="Opción 1"/>
                            <button type="button" class="opcion btn btn-opcion m-0 btn-success" opcion="1">
                                <i class="fas fa-check"></i>
                            </button>                                                    
                        </div>
                        <div class="form-group d-flex col-12 col-sm-6">
                            <input class="form-control" name="opcion_2" type="text" placeholder="Opción 2"/>
                            <button type="button" class="opcion btn btn-opcion m-0 btn-danger" opcion="2">
                                <i class="fas fa-times"></i>
                            </button>                                                    
                        </div>
                        <div class="form-group d-flex col-12 col-sm-6">
                            <input class="form-control" name="opcion_3" type="text" placeholder="Opción 3"/>
                            <button type="button" class="opcion btn btn-opcion m-0 btn-danger" opcion="3">
                                <i class="fas fa-times"></i>
                            </button>                                                    
                        </div>
                        <div class="form-group d-flex col-12 col-sm-6">
                            <input class="form-control" name="opcion_4" type="text" placeholder="Opción 4"/>
                            <button type="button" class="opcion btn btn-opcion m-0 btn-danger" opcion="4">
                                <i class="fas fa-times"></i>
                            </button>                                                    
                        </div>
                        <?php }?>
                    </div>
                    
                    <form class="text-center" id="formPregunta" name="formPregunta" action="<?=CTRL_EXAMENES?>" method="POST" novalidate>
                        <input type="hidden" name="id" value="<?=$datos['id']?>"/>
                        <input type="hidden" id="datos" name="datos" value=""/>
                        <div class="d-flex justify-content-around">
                            <?php
                            if($accesoFormulario=='crear'){?>
                            <button class="btn primary-color my-4" type="submit" name="crearPregunta">Crear</button>
                            <?php } 
                            elseif($accesoFormulario=='modificar'){?>
                            <button class="btn primary-color my-4" type="submit" name="editarPregunta">Modificar</button>
                            <?php }?>
                            <button class="btn secondary-color primary-dark-color-text my-4" type="submit" name="volver">
                               Volver
                            </button>
                        </div>
                    </form>
                    <div class="col-lg-2"></div>
                </div>
            </div>                        
        </main>
        
        <?php //jQuery ?>
        <script type="text/javascript" src="../js/jquery/jquery.min.js"></script>
        <?php //Bootstrap tooltips ?>
        <script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
        <?php //Bootstrap core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/bootstrap.min.js"></script>
        <?php //MDB core JavaScript ?>
        <script type="text/javascript" src="../js/bootstrap/mdb.min.js"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
        <!-- Your custom scripts (optional) -->
        <script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
        <!-- Script de validación -->        
        <script type = "text/javascript" src="../js/preguntas.js"></script>  
        <script type="text/javascript" src="../js/varios.js"></script>
    </body>
</html>
