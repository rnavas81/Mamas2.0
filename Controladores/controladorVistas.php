<?php
require_once '../Modelos/GestionUsuarios.php';

if(isset($_REQUEST['loginAlSubm'])) {
    $dni = $_REQUEST['dniAl'];
    $entra = GestionUsuarios::canLogin($dni, $_REQUEST['pasAl']);
    echo 'Login = '.$entra;;
}
?>
