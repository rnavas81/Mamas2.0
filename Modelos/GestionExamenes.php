<?php
/**
 * Clase que se encarga de gestionar los examenes en la base de datos
 *
 * @author dario
 */
require_once '../configuracion.php';
require_once 'GestionDatos.php';
require_once 'Examen.php';
require_once 'Usuario.php';

class GestionExamenes extends GestionDatos {
    
    /**
     * Crea un objeto Examen a partir de la base de datos
     * @param Array $datos
     * @return Examen
     */
    public static function formaExamen($datos=[]) {
        $examen = false;
        try {
            $examen = new Examen(
                    $datos['id'],
                    $datos['idProfesor'],
                    $datos['nombre'],
                    $datos['descripcion'],
                    $datos['fechaInicio'],
                    $datos['fechaFin'],
                    $datos['habilitado'],
                    $datos['activo']
                    );
            
        } catch (Exception $ex) {
            echo $exc->getTraceAsString();
        } finally {
            return $examen;
        }
    }
    
    /**
     * 
     * @param int $idProfesor id del profesor
     * @return Examen[] Devuelve un array de examenes
     */
    public function getExamen($idProfesor) {
        $examenes=[];
        $estaAbierta= self::isAbierta();
        $query = "SELECT * "
                . "FROM examenes "
                . "WHERE idProfesor =? AND habilitado = 1;";
        try {
            if(!$estaAbierta) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param('i',$idProfesor);            
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $examen = self::formaExamen($datos);
                if($examen)$examenes[]=$examen;                
            }            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
            $examenes = false;
        } finally {
            if(!$estaAbierta) {
                self::cerrarConexion();
            }
            return $examenes;
        }
    }
    
    /**
     * Recupera los examenes de un alumno segun se indique si están habilitados o no
     * @param type $activo estado de los examenes a buscar
     * @return Examen[] Devuelve un array de examenes
     */
    public function getExamenAlumno($activo,$idAlumno) {
        $examenes=[];
        $estaAbierta= self::isAbierta();
        $query = "SELECT e.* "
                . "FROM Examenes e "
                . "LEFT Join Alumnos_examenes r ON r.idExamen=e.id "
                . "WHERE r.idAlumno =? AND r.activo=? AND e.habilitado=1 ";
        try {
            if(!$estaAbierta) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param('ii',$idAlumno,$activo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $examen = self::formaExamen($datos);
                if($examen)$examenes[]=$examen;                
            }            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
            $examenes = false;
        } finally {
            if(!$estaAbierta) {
                self::cerrarConexion();
            }
            return $examenes;
        }
    }
    
    /**
     * Activa o desactiva el examen indicado por id
     * @param int $activar 0 para desactivar, 1 para activar
     * @param int $id id del examen a cambiar
     */
    public function activacionExamen($id,$activar) {
        $estabaAbierta=self::isAbierta();
        $query="UPDATE Examenes "
                . "SET activo = ".$activar." "
                . "WHERE id = ".$id.";";
        try {
            if(!$estabaAbierta) {
                self::abrirConexion();
            }
            self::$conexion->query($query);
        } catch (Exception $ex) {
            echo $ex->getTraceAsString(); 
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
        }
    }
    
    public function deleteExamen() {
        
    }
    
}
