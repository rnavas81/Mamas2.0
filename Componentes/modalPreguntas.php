<?php
/* 
 * Ventana modal con preguntas pregargadas
 */

require_once '../configuracion.php';

// Comprueba si la sesión está ya iniciada, si no la inicia
if(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
$usuarioActivo = null;
if(isset($_SESSION['usuario'])) {
    $usuarioActivo = $_SESSION['usuario'];
}
$datosPreguntas = [];
?>
<div class="modal" id="modalPreguntas" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Listado de preguntas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php 
      foreach ($datosPreguntas as $pregunta) {?>
          <div class="d-flex" data="<?=json_encode($pregunta)?>">
              <p class="col"><?=$pregunta['enunciado']?></p>
              <input type="checkbox" id="<?=$pregunta['id']?>"/>
          </div>
      <?php }?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="agregarPreguntas">Agregar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>