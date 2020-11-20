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
     * @return Usuario Devuelve el usuario si el login es correcto, si no false
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
    /**
     * Función que comprueba si un valor de la tabla usuarios existe    
     * @param $campo Columna a buscar en la tabla
     * @param $valor Valor que se quiere comprobar
     * @return int Devuelve 0 si no esta duplicado
     */
    public static function isDuplicado($campo, $valor) {
        if(!self::isAbierta()) {
            self::abrirConexion();
        }
        $response = false;
        $query = "SELECT count(*) AS contar "
                . "FROM usuarios "
                . "WHERE ".$campo."=? AND habilitado = 1;";
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("s",$valor);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($datos = $resultado->fetch_assoc()) {
                $response = $datos['contar'];
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();            
        } finally {
            if(self::isAbierta()) {
                self::cerrarConexion();
            }
            return $response;
        }         
    }
    
    /**
     * 
     * @param type $usuario Objeto usuario con los datos a registrar
     * @param type $password Contraseña del usuario a registrar
     */
    public static function registraUsuario($usuario,$password="") {
        if(!self::isAbierta()) {
            self::abrirConexion();
        }
        $response = false;        
        $password = self::encriptarPassword($password);
        $date = date_create($usuario->getFechaNacimiento());
        $date = date_format($date, 'Y-m-d');  
        $query1 = "INSERT INTO usuarios (nombre, apellidos, dni, password, fechaNacimiento, email) "
                . "VALUES ('".$usuario->getNombre()."', '".$usuario->getApellidos()."', '".$usuario->getDni()."', '".$password."', '".$date."', '".$usuario->getEmail()."')";              
        try {                       
            if (self::$conexion->query($query1)) {        
                $id = self::$conexion->insert_id;
                $query2= "INSERT INTO usuarios_roles (idUsuario, idRol) VALUES ";
                foreach ($usuario->getRoles() as $key=>$rol) {
                    if($key>0){
                        $query2.=",";
                    }
                    $query2.= "(".$id.",".$rol.")";
                }                                                                        
                self::$conexion->query($query2);                
            }            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();            
        } finally {
            if(self::isAbierta()) {
                self::cerrarConexion();
            }
            return $response;
        }        
    }
}
