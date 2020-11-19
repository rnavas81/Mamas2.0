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
switch ($tipoOpciones) {
    case 'administradorDashboard':
        $opciones = [
          ['label'=>'Administradores','name'=>'datosAdministradores'],
          ['label'=>'Profesores','name'=>'datosProfesores'],
          ['label'=>'Alumnos','name'=>'datosAlumnos'],
        ];
        break;

    default:
        break;
}

?>
<nav id="sidebar" class="sticky-top">
    <div id="dismiss">
        <i class="fas fa-arrow-left"></i>
    </div>
    <div class="sidebar-header">
        <h3>Mamas 2.0</h3>
    </div>
    <form action="<?=CTRL_ADMIN?>" method="POST">
        <ul class="list-unstyled components">
            <p><?=$tituloMenu?></p>
            <?php 
            foreach ($opciones as $opcion) {?>
            <li class="d-flex justify-content-center">
                <input class="btn btn-block my-2" type="submit" value="<?= $opcion['label'] ?>" name="<?= $opcion['name'] ?>" />
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

<header>            
    <nav class="mb-1 navbar sticky-top navbar-expand-lg navbar-dark default-color">                  
        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>                               
        </button>             
        <div class="ml-auto" id="navbarSupportedContent-333">                                        
            <ul class="navbar-nav ml-auto nav-flex-icons">
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
