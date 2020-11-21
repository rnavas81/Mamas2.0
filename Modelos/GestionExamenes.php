<?php
/**
 * Classe que se encarga de gestionar los examenes en la base de datos
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
     * Recupera los examenes segun se indique si están habilitados o no
     * @param type $activo estado de los examenes a buscar
     * @return Examen[] Devuelve un array de examenes
     */
    public function getExamen($activo) {
        $examenes=[];
        $estaAbierta= self::isAbierta();
        $query = "SELECT * "
                . "FROM examenes "
                . "WHERE habilitado = 1 AND activo =?";
        try {
            if(!$estaAbierta) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param('i',$activo);
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
    
    public function deleteExamen() {
        
    }
    
}
