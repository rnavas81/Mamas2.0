<?php
/**
 * Manejador para la transición de datos de Usuarios con la persistencia de datos
 * CRUD
 * Acceso
 *
 * @author rodrigo
 */
require_once 'GestionDatos.php';
require_once 'Usuario.php';
class GestionUsuarios {
    /**
     * Recupera los datos para crear un nuevo elemento Usuario
     * @param  $datos
     * @return Usuario
     */
    public static function recuperarUsuario($datos) {
        $usuario = new Usuario(
                $datos['dni'],
                $datos['username'],
                $datos['tipo'],
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['avatar'],
                isset($datos['validado'])?$datos['validado']:0,
        );
        return $usuario;
    }
    /**
     * Recupera un usuario por el username y el password
     * @param String $username
     * @param String $password
     */
    public static function getUsuario($username='',$password='') {
        $persona = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT * FROM Usuarios WHERE username=? AND password=? AND habilitado=1 AND validado=1';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("ss", $username,$password);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if ($fila = $result->fetch_assoc()) {
                    $persona=self::recuperarUsuario($fila);
                }                
            } else {
                exit($stmt->error());
            }
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $persona=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $persona;
        }    
    }
    /**
     * Cambia el password de un usuario que contenga un email
     * @param String $email email del usuario
     * @param String $password nueva contraseña
     */
    public static function setUsuarioPasswordByEmail($email,$password) {
        $persona = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'UPDATE Usuarios SET password=? WHERE email=?';
        try {
            if (!$estabaAbierta) {
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("ss", $password,$email);
            $persona = $stmt->execute();
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $persona=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $persona;
        }    
    }

    public static function addUsuario($p,$password,$codigo="") {
        $persona = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'INSERT INTO Usuarios (dni,username,password,tipo,nombre,apellidos,email,avatar,validado,validacion)'
                . 'VALUES ('
                . '\''.$p->getDni().'\','
                . '\''.$p->getUsername().'\','
                . '\''.$password.'\','
                . '\''.$p->getTipo().'\','
                . '\''.$p->getNombre().'\','
                . '\''.$p->getApellidos().'\','
                . '\''.$p->getEmail().'\','
                . '\''.$p->getAvatar().'\','
                . '\''.$p->getValidado().'\','
                . '\''.$codigo.'\''
                . ');';
        try {
            if (!$estabaAbierta) {
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $persona = $stmt->execute();
            $stmt->close();
            if(strlen(GestionDatos::$conexion->error())>0){
                exit(GestionDatos::$conexion->error());
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $persona=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $persona;
        }        
    }
    /**
     * Actualiza los datos de un usuario
     * @param type $p
     * @param type $password
     */
    public static function updateUsuario($p,$password=""){
        $hecho = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'UPDATE Usuarios SET '
                . 'username=\''.$p->getUsername().'\''
                . ',tipo=\''.$p->getTipo().'\''
                . ',nombre=\''.$p->getNombre().'\''
                . ',apellidos=\''.$p->getApellidos().'\''
                . ',email=\''.$p->getEmail().'\'';
        
        if(!empty($password))$query.=',password = \''.$password.'\' ';
        $query.=' WHERE dni=\''.$p->getDni().'\';';
        try {
            if (!$estabaAbierta) {
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $hecho = $stmt->execute();
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            exit();
            $hecho=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $hecho;
        }      
        
    }
    /**
     * Comprueba si existe un usuario con el mismo dni,username o email
     * @param Object $data
     * @return Objeto con las respuestas
     */
    public static function hasUsuarios($p) {
        $respuesta = [
            'dni'=> self::contarUsuarios('dni', $p->getDni()),
            'username'=> self::contarUsuarios('username', $p->getUsername()),
            'email'=> self::contarUsuarios('email', $p->getEmail())
        ];    
        return $respuesta;
    }
    /**
     * Comprueba cuantos usuarios existen con el valor de un campo determinado
     * @param String $campo
     * @param String $valor
     * @return boolean true => si no hay usuarios con el valor en el campo
     */
    private static function contarUsuarios($campo,$valor){
        $respuesta = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT COUNT(*) AS cuantos FROM Usuarios WHERE '.$campo.'=? AND habilitado=1';
        try {
            if (!$estabaAbierta) GestionDatos::abrirConexion();
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("s", $valor);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($fila = $result->fetch_assoc()) $respuesta = $fila['cuantos']==0;
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $respuesta=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $respuesta;
        }      
        
    }
    /**
     * Valida al usuario que contiene un código y lo devuelve
     */
    public static function validarUsuario($codigo){
        $persona = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT * FROM Usuarios WHERE validacion=? AND validado=0';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("s", $codigo);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if ($fila = $result->fetch_assoc()) {
                    $persona=self::recuperarUsuario($fila);
                    $query2='UPDATE Usuarios SET validado=1,validacion=\'\' WHERE dni=?';
                    $stmt = GestionDatos::$conexion->prepare($query2);
                    $stmt->bind_param("s", $dni);
                    $dni=$persona->getDni();
                    if(!$stmt->execute()){
                        $persona = false;
                    }
                }                
            } else {
                $persona = false;
            }
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $persona=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $persona;
        }    

    }
    /**
     * Recupera todos los usuarios
     */
    public static function getUsuarios() {
        $usuarios = [];
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT * FROM Usuarios WHERE habilitado=1 ORDER BY apellidos,nombre';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            if($stmt->execute()){
                $result = $stmt->get_result();
                while ($fila = $result->fetch_assoc()) {
                    $persona=self::recuperarUsuario($fila);
                    if($persona)$usuarios[]=$persona;
                }                
            } else {
                exit($stmt->error());
            }
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $usuarios=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $usuarios;
        }    
        
    }
    /**
     * Recupera los datos de un usuario por su DNI
     * @param String $dni
     */
    public static function getUsuarioByDni($dni) {
        $persona = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT * FROM Usuarios WHERE dni=? AND habilitado=1 AND validado=1';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("s", $dni);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if ($fila = $result->fetch_assoc()) {
                    $persona=self::recuperarUsuario($fila);
                }                
            } else {
                exit($stmt->error());
            }
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $persona=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $persona;
        }    
        
    }
    /**
     * Recupera los perfiles para los usuarios
     * @return Array
     */
    public static function getNiveles() {
        $niveles = [];
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'SELECT * FROM Auxiliar WHERE tipo=1 AND habilitado=1;';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            if($stmt->execute()){
                $result = $stmt->get_result();
                while ($fila = $result->fetch_assoc()) {
                    $niveles[]=[
                        "id"=>$fila["id"],
                        "nombre"=>$fila["nombre"],
                        "descripcion"=>$fila["descripcion"]
                    ];
                }                
            }
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $niveles=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $niveles;
        }  
    }
    /**
     * Elimina un usuario
     * @param String $dni
     * @return boolean
     */
    public static function deleteUsuario($dni) {
        $hecho = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'UPDATE Usuarios SET habilitado=0 WHERE dni=?';
        try {
            if (!$estabaAbierta) {
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("s", $dni);
            $hecho = $stmt->execute();
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $hecho=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $hecho;
        }    
        
    }
    /**
     * Valida un usuario
     * @param String $dni
     */
    public static function validarUsuarioByDni($dni) {
        $hecho = false;
        $estabaAbierta= GestionDatos::isAbierta();
        $query = 'UPDATE Usuarios SET validacion=\'\',validado=1 WHERE dni=?';
        try {
            if(!$estabaAbierta){
                GestionDatos::abrirConexion();
            }
            $stmt = GestionDatos::$conexion->prepare($query);
            $stmt->bind_param("s", $dni);
            $hecho=$stmt->execute();
            $stmt->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $hecho=false;
        } finally {
            if(!$estabaAbierta){
                GestionDatos::cerrarConexion();
            }
            return $hecho;
        }    
        
    }

}
