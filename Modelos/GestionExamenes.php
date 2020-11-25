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
                . "FROM Examenes "
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
    public function getExamenAlumno($realizado,$idAlumno) {
        $examenes=[];
        $estaAbierta= self::isAbierta();
        $query = "SELECT e.* "
                . "FROM Examenes e "
                . "LEFT Join Alumnos_examenes r ON r.idExamen=e.id "
                . "WHERE r.idAlumno =? AND r.realizado=? AND e.habilitado=1 "
                . "ORDER BY e.fechaInicio DESC";
        try {
            if(!$estaAbierta) {
                self::abrirConexion();
            }
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param('ii',$idAlumno,$realizado);
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
    
    /**
     * Funcion que borra el examen seleccionado (no se borra de la BBDD)
     * @param int $id Id del examen a borrar
     */
    public function deleteExamen($id) {
        $estabaAbierta=self::isAbierta();
        $query="UPDATE Examenes "
                . "SET habilitado = 0 "
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
    
    public static function insertExamen($data,$idProfesor=0) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "INSERT INTO Examenes (idProfesor,nombre,descripcion,fechaInicio,fechaFin,activo) "
                . "VALUES (?,?,?,?,?,?)";
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $fechaInicio = empty($data['fechaInicio'])?null:$data['fechaInicio'];
        $fechaFin = empty($data['fechaFin'])?null:$data['fechaFin'];
        $activo = $data['activo'];
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("issssi",$idProfesor,$nombre,$descripcion,$fechaInicio,$fechaFin,$activo);
            if ($stmt->execute()) {        
                $id = self::$conexion->insert_id;
                $query2= "INSERT INTO Examenes_Preguntas(idExamen,enunciado,tipo,opciones) VALUES ";
                $valores = [];
                foreach ($data['preguntas'] as $index=>$pregunta) {
                    $valores[]= "(".$id.",'"
                            . "".$pregunta['enunciado']."',"
                            . "".$pregunta['tipo'].""
                            . ",'". json_encode($pregunta['opciones'])."'   )";
                }
                $query2.= implode(",", $valores);
                $stmt = self::$conexion->prepare($query2);
                $response=$stmt->execute();
            }
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
    }

    public static function getExamenById($id) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $query = "SELECT * FROM Examenes WHERE id=?";
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("i",$id);
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if($data = $resultado->fetch_assoc()){
                    $response = [
                        "id"=>$id,
                        "nombre"=>$data['nombre'],
                        "descripcion"=>$data['descripcion'],
                        "activo"=>$data['activo'],
                        "fechaInicio"=>$data['fechaInicio'],
                        "fechaFin"=>$data['fechaFin'],
                        "preguntas"=>[]
                    ];
                    $queryPreguntas = "SELECT * FROM Examenes_Preguntas WHERE idExamen=?";
                    
                    if(!$estabaAbierta) self::abrirConexion();
                    $stmt2 = self::$conexion->prepare($queryPreguntas);
                    $stmt2->bind_param("i",$id);
                    if ($stmt2->execute()) {
                        $resultado2 = $stmt2->get_result();
                        while($pregunta= $resultado2->fetch_assoc()){
                            $response['preguntas'][]=[
                                "enunciado"=>$pregunta["enunciado"],
                                "tipo"=>$pregunta["tipo"],
                                "opciones"=> json_decode($pregunta["opciones"],true)
                            ];
                        }
                    }
                }
            }
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
    }

    public static function updateExamen($data,$id=0, $idProfesor=0) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "UPDATE Examenes SET "
                . "nombre=?,"
                . "descripcion=?,"
                . "fechaInicio=?,"
                . "fechaFin=?,"
                . "activo=? "
                . "WHERE id=? AND idProfesor=?;";
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $fechaInicio = empty($data['fechaInicio'])?null:$data['fechaInicio'];
        $fechaFin = empty($data['fechaFin'])?null:$data['fechaFin'];
        $activo = $data['activo'];
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("ssssiii",$nombre,$descripcion,$fechaInicio,$fechaFin,$activo,$id,$idProfesor);
            if ($stmt->execute()) {
                $queryBorraPreguntas = "DELETE FROM Examenes_Preguntas WHERE idExamen=?";
                $stmt = self::$conexion->prepare($queryBorraPreguntas);
                $stmt->bind_param("i",$id);
                $response=$stmt->execute();
                $query2= "INSERT INTO Examenes_Preguntas(idExamen,enunciado,tipo,opciones) VALUES ";
                $valores = [];
                foreach ($data['preguntas'] as $index=>$pregunta) {
                    $valores[]= "(".$id.",'"
                            . "".$pregunta['enunciado']."',"
                            . "".$pregunta['tipo'].""
                            . ",'". json_encode($pregunta['opciones'])."'   )";
                }
                $query2.= implode(",", $valores);
                $stmt = self::$conexion->prepare($query2);
                $response=$stmt->execute();
            }
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
        
    }

    public static function getPreguntasByIdUsuario($id) {
        $estabaAbierta=self::isAbierta();
        $response = []; 
        $query = "SELECT p.* "
                . "FROM Examenes_Preguntas p "
                . "LEFT JOIN Examenes e ON e.id=p.idExamen AND e.idProfesor=?;";
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("i",$id);
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while($pregunta = $resultado->fetch_assoc()){
                    $response[]=[
                        "id"=>$pregunta["id"],
                        "enunciado"=>$pregunta["enunciado"],
                        "tipo"=>$pregunta["tipo"],
                        "opciones"=> json_decode($pregunta["opciones"],true)
                    ];
                }
            }
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
        
    }
}
