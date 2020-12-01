<?php
/*
 * @author Rodrigo Navas / Darío León
 * Ventana modal con preguntas pregargadas
 */

require_once '../configuracion.php';
require_once '../Modelos/GestionExamenes.php';

// Comprueba si la sesión está ya iniciada, si no la inicia
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
$usuarioActivo = null;
if (isset($_SESSION['usuario'])) {
    $usuarioActivo = $_SESSION['usuario'];
}
$datosPreguntas = GestionExamenes::getPreguntasAlmacenByProfesor($usuarioActivo->getId());
?>
<div class="modal" id="modalPreguntas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg h-100 mt-1" role="document">
        <div class="modal-content h-100">
            <div class="modal-header primary-color white-text">
                <h5 class="modal-title">Listado de preguntas</h5>
                <button type="button" class="close secondary-color" data-dismiss="modal" aria-label="Close">
                    <span class="h3-responsive font-weight-bolder primary-dark-color-text" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="border-bottom d-flex pt-2 px-3">
                <!--<p class="col h5 m-0 p-0">Enunciado</p>-->
                <input class="col form-control mb-2 mr-2" type="search" id="filtroPreguntas" />
                <label class="align-self-center">
                    Todas
                    <input class="align-self-center" type="checkbox" id="marcarTodas"/>                  
                </label>
            </div>          
            <div class="modal-body overflow-auto">
                <?php
                foreach ($datosPreguntas as $pregunta) {?>
                    <div class="d-flex pregunta-blk" >
                        <p class="col"><?= $pregunta['enunciado'] ?></p>
                        <input name="pregunta-marcada" type="checkbox" id="<?= $pregunta['id'] ?>"/>
                        <data id="data-<?= $pregunta['id'] ?>" class="d-none"><?=json_encode($pregunta)?></data>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="agregarPreguntas">Agregar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>