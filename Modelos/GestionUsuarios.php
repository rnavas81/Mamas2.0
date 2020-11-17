<?php
/**
 * Description of GestionUsuarios
 *
 * @author dario
 */
require_once 'GestionDatos.php';
class GestionUsuarios extends GestionDatos {
    
    /**
     * Funcion para comprobar que los datos del login
     * son correctos
     * @param $dni DNI del usuario a comprobar
     * @param $password contraseña del usuario a comprobar
     * @return boolean Devuelve true si el login es correcto
     */
    public static function canLogin($dni,$password) {
        if(!self::isAbierta()) {
            self::abrirConexion();
        }
        $query = "SELECT * FROM usuarios "
                . "WHERE dni = '".$dni."' AND "
                . "password ='".$password."'";
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $entra = false;
            while ($resultado->fetch_assoc()) {
                $entra = true;
            }
            if(self::isAbierta()) {
                self::cerrarConexion();
            }            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        } finally {
            return $entra;
        }
    }
    
}
