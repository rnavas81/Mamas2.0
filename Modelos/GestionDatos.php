<?php
/**
 * Gestiona el acceso a los datos de la aplicación
 *
 * @author rodrigo
 */
require_once '../configuracion.php';

class GestionDatos {
    
    public static $conexion=false;
    
    public static function abrirConexion()
    {
        $abierta = false;
        try {
            self::$conexion = new mysqli(_PATH, _USER, _PASS, _DEFAULT);
            $abierta = self::$conexion->connect_errno()==0;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $abierta=false;
        } finally {
            return $abierta;
        }
    }
    public static function cerrarConexion(){
        if(self::$conexion!==false){
            self::$conexion->close();
            self::$conexion=false;
        }
    }
    public static function isAbierta(){
        return self::$conexion!=false;
    }
}
