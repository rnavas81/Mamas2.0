<?php
/**
 * Gestiona el acceso a los datos de la aplicación
 *
 * @author rodrigo
 */
class GestionDatos {
    
    //BASE DE DATOS
    private const _PATH = 'localhost';
    private const _USER = 'mamas';
    private const _PASS = 'Chubaca2020';
    private const _DEFAULT = 'Mamas';
    public static $conexion=false;
    
    public static function abrirConexion()
    {
        $abierta = false;
        try {
            self::$conexion = new mysqli(self::_PATH, self::_USER, self::_PASS, self::_DEFAULT);
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
