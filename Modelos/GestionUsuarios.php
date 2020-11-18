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
            return $finally;
        }        
    }
    
    private function encriptarPassword($password){
        return hash('sha256',$password);
    }
    
    /**
     * Funcion para comprobar que los datos del login
     * son correctos
     * @param $dni DNI del usuario a comprobar
     * @param $password contraseña del usuario a comprobar
     * @return boolean Devuelve true si el login es correcto
     */
    public static function canLogin($dni,$password) {
        $usuario = false;
        if(!self::isAbierta()) {
            self::abrirConexion();
        }
        $query = "SELECT u.*,group_concat(r.idRol) AS roles "
                . "FROM Usuarios u "
                . "LEFT Join Usuarios_Roles r ON r.idUsuario=u.id "
                . "WHERE u.dni = ? AND u.password = ?;"
                . "GROUP BY u.id";
        try {
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("ss",$dni, $this->encriptarPassword($password));
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($datos = $resultado->fetch_assoc()) {
                $usuario = self::formarUsuario($datos);
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
            $usuario = false;
        } finally {
            if(self::isAbierta()) {
                self::cerrarConexion();
            }            
            return $usuario;
        }
    }

    public static function actualizarPasswords() {
        $query="SELECT * FROM Usuarios";
        try {;
            if(!self::isAbierta()) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $queryUpdate="UPDATE Usuarios SET password='".hash('sha256',$datos['password'])."' WHERE id=$datos[id]";
                echo $queryUpdate."<br>";
                $stmt2 = self::$conexion->prepare($queryUpdate);
                $stmt2->execute();
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        } finally {
            if(self::isAbierta()) {
                self::cerrarConexion();
            }            
        }
    }

}
