<!DOCTYPE html>
<?php

require_once '../configuracion.php';
require_once '../Modelos/GestionExamenes.php';
require_once '../Modelos/Usuario.php';
// Comprueba si la sesión está ya iniciada, si no la inicia
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
isset($_SESSION['usuario']) or header("Location " . CTRL_BASICO);

//Comprueba que hay un usuario logueado y tiene permisos de alumno
isset($_SESSION['usuario']) && ($_SESSION['usuario']->hasRol(3)) or header("Location: " . CTRL_BASICO);
//Recupera un posible mensaje a mostrar
$msg = null;
if (isset($_SESSION['MSG_INFO'])) {
    $msg = $_SESSION['MSG_INFO'];
    unset($_SESSION['MSG_INFO']);
}

$data = $_SESSION['examenAct'];
$respuestas = GestionExamenes::getRespuestasAlumno($_SESSION['usuario']->getId(), $data['id']);

if (isset($_SESSION['alumnoTipo'])) {
    $tipo = $_SESSION['alumnoTipo'];
}
$tipoOpciones = "alumnosDashboard";
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
                            <p class="form-control">
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
                                        <li class="borderLine white form-group d-flex col-12 col-sm-6 order-<?=($indexP+1)?> <?= $opcionP['correcta'] ? 'correcta' : '' ?>" name="<?= ($indexP+1)?>">
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
                                            <li class="borderLine white form-group d-flex col-12 col-sm-6 respuesta" name="<?=$key?>">
                                                <?=$aux?>
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
                                        <ul class="form-row opciones px-3 lista" name="lista">
                                            <?php
                                            foreach ($pregunta['opciones'] as $indexP => $opcionP) {
                                            ?>
                                            <li class="borderLine white form-group d-flex col-12 col-sm-6 order-<?=($indexP+1)?> <?= $opcionP['correcta'] ? 'correcta' : '' ?>" name="<?= ($indexP+1)?>">
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
                                                <li class="borderLine white form-group d-flex col-12 col-sm-6 respuesta" name="<?=$key?>">
                                                    <?=$aux?>
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

</html>