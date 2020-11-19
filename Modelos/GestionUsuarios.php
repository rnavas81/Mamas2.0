<?php
/**
 * Description of GestionUsuarios
 *
 * @author dario
 */
require_once '../configuracion.php';
require_once 'GestionDatos.php';
require_once 'Usuario.php';

class GestionUsuarios extends GestionDatos {
    /**
     * Crea un objeto Usuario en base a datos
     * @param Array $datos
     * $datos[roles] debe ser un string con los idRol separado por comas
     */
    public static function formarUsuario($datos=[]){
        $usuario = false;
        $roles=[];
        if(isset($datos['roles'])){
            $roles = explode(',', $datos['roles']);
        }
        try {
            $usuario = new Usuario(
                    $datos['id'], 
                    $datos['dni'], 
                    $datos['nombre'], 
                    $datos['apellidos'], 
                    $datos['fechaNacimiento'], 
                    $datos['email'], 
                    $roles
                    );
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        } finally {
            return $usuario;
        }        
    }
    
    private static function encriptarPassword($password){
        try {
            return hash('sha256',$password);    
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return null;
        }

    }
    
    /**
     * Funcion para comprobar que los datos del login
     * son correctos
     * @param $dni DNI del usuario a comprobar
     * @param $password contraseña del usuario a comprobar
     * @return boolean Devuelve true si el login es correcto
     */
    public static function canLogin($dni,$password) {
        $response = false;
        $estabaAbierta=self::isAbierta();
        $query = "SELECT u.*,group_concat(r.idRol) AS roles "
                . "FROM Usuarios u "
                . "LEFT Join Usuarios_Roles r ON r.idUsuario=u.id "
                . "WHERE u.dni = ? AND u.password = ? "
                . "GROUP BY u.id;";
        try {
            if(!$estabaAbierta) {
                self::abrirConexion();
            }
            $password= self::encriptarPassword($password);
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ss",$dni, $password);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($datos = $resultado->fetch_assoc()) {
                $response = self::formarUsuario($datos);
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
            $response = false;
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }            
            return $response;
        }
    }

    /**
     * Recupera los usuarios que contengan un rol determinado
     * @param int $rol
     */
    public function getUsuarios($rol) {
        $usuarios=[];
        $estabaAbierta=self::isAbierta();
        $query="SELECT u.*,GROUP_CONCAT(r.idRol) AS roles "
                . "FROM Usuarios u "
                . "RIGHT JOIN Usuarios_Roles r ON r.idUsuario=u.id AND r.idRol=? "
                . "WHERE u.habilitado=1 "
                . "GROUP BY u.id "
                . "ORDER BY u.apellidos,u.nombre,u.dni;";
        try {
            if(!$estabaAbierta) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param('i',$rol);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $usuario = self::formarUsuario($datos);
                if($usuario)$usuarios[]=$usuario;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
            $usuarios = false;
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
            return $usuarios;
        }

        
    }


}
