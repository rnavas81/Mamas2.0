<!DOCTYPE html>
<?php
/**
 * @author Darío León
 * Pantalla en la que un profesor podrá corregir un examen
 * 
 */
require_once '../configuracion.php';
require_once '../Modelos/GestionExamenes.php';
require_once '../Modelos/Usuario.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
isset($_SESSION['usuario']) or header("Location " . CTRL_BASICO);

//Comprueba que hay un usuario logueado y tiene permisos de administrador o profesor
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(1) || $_SESSION['usuario']->hasRol(2)) OR header("Location: ".CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if (isset($_SESSION['MSG_INFO'])) {
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}

if(isset($_SESSION['profesorTipo'])){
    $tipo = $_SESSION['profesorTipo'];
}

$data = $_SESSION['examenAct'];
$respuestas = GestionExamenes::getRespuestasAlumno($_SESSION['idAlumnoAct'], $data['id']);

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

    <main class="">
        <div class="container-fluid">
            <div class="d-flex">
                <span id="msg" class="col-12 text-center"><?= $msg ?></span>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-10 py-3">
                    <div class="container">
                        <!--Nombre del examen -->
                        <div class="form-group">
                            <p class="form-control h-auto">
                                <?= $data['nombre'] ?>
                            </p>
                        </div>
                        <div class="">
                            <ul class="list-group list-group-flush" id="lista-preguntas">
                                <?php
                                if (count($data['preguntas']) > 0) {
                                foreach ($data['preguntas'] as $index => $pregunta) { ?>
                                <li class="list-group-item border p-2 mb-3" name="pregunta" id="<?= $pregunta['id'] ?>">
                                    <div class="form-group mb-2">
                                        <p name="enunciado" class="h5 col text-left"><?= $pregunta['enunciado'] ?></p>
                                    </div>
                                    <?php
                                    if ($pregunta['tipo'] === 1) {
                                    ?>
                                    <p class="form-control respuestaText" name="respuesta" readonly>
                                        <?=$respuestas[$pregunta['id']]?>
                                    </p>
                                    <button type="button" class="btn btn-sm btn-dark-green my-0 float-right mr-5 btnAcierto" title="Marcar como correcta">
                                        Correcta
                                    </button>
                                    <button type="button" class="btn btn-sm btn-grey my-0 float-right btnFallo" title="Marcar como incorrecta">
                                        Incorrecta
                                    </button>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($pregunta['tipo'] === 2) {
                                        ?>                                    
                                    <ul class="form-row opciones px-3" name="lista">
                                        <?php
                                        foreach ($pregunta['opciones'] as $indexP => $opcionP) {
                                        ?>
                                        <li class="borderLine white form-group d-flex col-12 col-sm-6 order-<?=($indexP+1)?> name="<?= ($indexP+1)?>">
                                            <div class="col"><?= $opcionP['texto'] ?></div>
                                        </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                    <div class="form-group">
                                        <p>Repuesta</p>
                                        <ul class="sortable unica form-row py-2 px-3 secondary-light-color primary-dark-color-text" name="respuestas">
                                        <?php                                        
                                        $arrResp = explode(',', $respuestas[$pregunta['id']]);
                                        foreach ($arrResp as $key => $aux) {                                            
                                        ?>                                                
                                            <li class="borderLine white form-group d-flex col-12 col-sm-6 respuesta <?=$pregunta['opciones'][$key]['correcta']?'correcta':''?>" name="<?=$key?>">
                                                <div class="col"><?= $pregunta['opciones'][$key]['texto'] ?></div>
                                            </li>                                                
                                        <?php
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($pregunta['tipo'] === 3) {
                                    ?>    
                                        <input class="multi-val" value="" type="hidden">
                                        <ul class="form-row opciones px-3 lista" name="lista">
                                            <?php
                                            foreach ($pregunta['opciones'] as $indexP => $opcionP) {                                                
                                            ?>                                            
                                            <li class="borderLine white form-group d-flex col-12 col-sm-6 order-<?=($indexP+1)?> <?= $opcionP['correcta'] ? 'corrOpt' : '' ?> name="<?= ($indexP+1)?>">
                                                <div class="col"><?= $opcionP['texto'] ?></div>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="form-group">
                                            <p>Repuesta</p>                                            
                                            <ul class="sortable form-row py-2 px-3 secondary-light-color primary-dark-color-text" name="respuestas">
                                            <?php                                            
                                            $arrResp = explode(',', $respuestas[$pregunta['id']]);
                                            foreach ($arrResp as $key => $aux) {
                                            ?>                                                
                                                <li class="borderLine white form-group d-flex col-12 col-sm-6 respuesta <?=$pregunta['opciones'][$key]['correcta']?'correcta':''?>" name="<?=$key?>">
                                                    <div class="col"><?= $pregunta['opciones'][$key]['texto'] ?></div>
                                                </li>                                                
                                            <?php
                                            }
                                            ?>
                                            </ul>
                                        </div>                                                                           
                                    <?php
                                    }
                                    ?>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>                            
                            <div class="row" role="group">                            
                                <form type="POST" class="col text-center" action="<?=CTRL_PROFESORES?>">
                                    <input id="notasFin" name="notasFin" value="" type="hidden">
                                    <button class="btn btn-primary btn-sm" type="button" id="terminarCorrecion" data-toggle="modal" data-target="#confirmarCorrecion">
                                        Corregir
                                    </button>
                                    <button class="btn secondary-color btn-sm primary-dark-color-text" type="button" onclick="history.go(-1)" name="volver">
                                       Cancelar
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="confirmarCorrecion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">TERMINAR CORRECION</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body text-center">
                                                <p id="mensajeModal"></p> 
                                                ¿Desea continuar?
                                            </div>
                                            <div class="text-center pb-2">
                                                <button id="terminarExamen" name="terminarCorrecion"  type="submit" class="btn btn-danger" title="Eliminar">
                                                    CONTINUAR
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>                                             
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                                                                    
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<?php //jQuery 
?>
<script type="text/javascript" src="../js/jquery/jquery.min.js"></script>
<?php //Bootstrap tooltips 
?>
<script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
<?php //Bootstrap core JavaScript 
?>
<script type="text/javascript" src="../js/bootstrap/bootstrap.min.js"></script>
<?php //MDB core JavaScript 
?>
<script type="text/javascript" src="../js/bootstrap/mdb.min.js"></script>
<?php //jQuery Custom Scroller CDN 
?>
<script type="text/javascript" src="../js/jquery/jquery.mCustomScrollbar.min.js"></script>
<?php //Your custom scripts (optional) 
?>
<script type="text/javascript" src="../js/bootstrap/sidebar.js"></script>
<script type="text/javascript" src="../js/examenFormulario.js"></script>
<script type="text/javascript" src="../js/varios.js"></script>
<script type="text/javascript" src="../js/correcion.js"></script>

</html>