<?php 
/**
 * @author Darío León
 * Componenete para generar un menú lateral y una barra de herramientas superior 
 * con un botón pàra desplegar el menú
 * 
 * Requerimientos:
 * 
 ** CSS
 * css/sidebar.css
 * 
 ** Scripts
 * ../js/jquery.min.js
 * ../js/jquery/jquery.mCustomScrollbar.min.js
 * ../js/sidebar.js
 * 
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
$tituloMenu="Hola";
if( $usuarioActivo ){
    $tituloMenu.="<br>".$usuarioActivo->getNombre()." ".$usuarioActivo->getApellidos();
}
//Según el valor que recoja $tipoOpciones carga unos valores en el menú
isset($tipoOpciones) OR $tipoOpciones=null;
$opciones=[];
//Acción del formulario, se determina por el tipo de opciones, por defecto basico
$controladorAct="";
switch ($tipoOpciones) {
    case 'administradorDashboard':
        $controladorAct = CTRL_ADMIN;
        $opciones = [
          ['label'=>'Administradores','name'=>'datosAdministradores'],
          ['label'=>'Profesores','name'=>'datosProfesores'],
          ['label'=>'Alumnos','name'=>'datosAlumnos'],
        ];
        $accion = CTRL_ADMIN;
        break;
    case 'profesorDashboard':
        $controladorAct = CTRL_PROFESORES;
        $opciones = [
          ['label'=>'Examenes','name'=>'examenes'],
          ['label'=>'Preguntas','name'=>'preguntas'],          
        ];
        break;
    case 'alumnosDashboard':
        $controladorAct = CTRL_ALUMNOS;
        $opciones = [
          ['label'=>'Examenes','name'=>'examenesPendientes'],
          ['label'=>'Progresion','name'=>''],          
        ];
        break;
    default:
        break;
}

?>
<nav id="sidebar" class="sticky-top primary-color">
    <div id="dismiss" class="primary-dark-color">
        <i class="fas fa-arrow-left"></i>
    </div>
    <div class="sidebar-header primary-light-color">
        <h3>Mamas 2.0</h3>
    </div>
    <form action="<?=$controladorAct?>" method="POST">
        <ul class="list-unstyled components">
            <p><?=$tituloMenu?></p>
            <?php 
            foreach ($opciones as $opcion) {?>
            <li class="d-flex justify-content-center btn btn-block my-2">
                <input class="btn btn-sm shadow-none" type="submit" value="<?= $opcion['label'] ?>" name="<?= $opcion['name'] ?>" />
            </li>
            <?php }?>
        </ul>        
    </form>
    <?php
    if($usuarioActivo){?>
    <ul class="list-unstyled CTAs">
        <li>
            <a href="<?=CTRL_BASICO?>?accion=salir" class="download">
                Salir
            </a>
        </li>
    </ul>
    <?php }?>
</nav>

<div class="overlay"></div> 

<header class="sticky-top">            
    <nav class="mb-1 navbar navbar-expand-lg navbar-dark primary-color">                  
        <button type="button" id="sidebarCollapse" class="btn primary-dark-color btn-sm">
            <i class="fas fa-bars"></i>                               
        </button>             
        <div class="ml-auto" id="navbarSupportedContent-333">                                        
            <ul class="navbar-nav ml-auto nav-flex-icons">
                <?php
                if(count($usuarioActivo->getRoles())>1 || $usuarioActivo->hasRol(ROL_ADMINISTRADOR)){?>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="<?=WEB_ENTRADA_MULTI?>" title="Cambiar de rol">
                        <i class="fas fa-dice-d20 fa-spin"></i>
                    </a>
                </li>                    
                <?php }?>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="<?=CTRL_BASICO?>?accion=editarPrefil" title="Editar perfil">
                        <i class="fas fa-user-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="<?=CTRL_BASICO?>?accion=salir" title="Salir">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>            
</header>

