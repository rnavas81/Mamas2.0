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
                    $datos['activo'],
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
    public function getExamenesByProfesor($idProfesor) {
        $examenes=[];
        $estaAbierta= self::isAbierta();
        $query = "SELECT * "
                . "FROM Examenes "
                . "WHERE idProfesor =? AND habilitado = 1 "
                . "ORDER BY fechaInicio DESC";
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
                . "WHERE r.idAlumno =? AND r.realizado=? AND e.habilitado=1 AND e.activo = 1 "
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
            $query2 = "SELECT nota , idExamen "
                    . "FROM alumnos_examenes "
                    . "WHERE idAlumno = ?";
            $stmt = self::$conexion->prepare($query2);
            $stmt->bind_param('i',$idAlumno);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                foreach ($examenes as $aux) {
                    if($aux->getId()=== $datos['idExamen']) {
                        $aux->setNota($datos['nota']);
                    }
                }
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
                                "id"=>$pregunta["id"],
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
                $idExamen = self::$conexion->insert_id;
                foreach ($data['preguntas'] as $pregunta) {
                    self::insertExamenPregunta($pregunta, $idExamen);
                    if($pregunta['almacenar']==1){
                        self::insertPreguntaAlmacen($pregunta, $idProfesor);
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
                $ids = [];
                foreach ($data['preguntas'] as $pregunta) {
                    if($pregunta["id"]==0){//Guardar pregunta
                        self::insertExamenPregunta($pregunta, $id);
                    } else {
                        self::UpdateExamenPregunta($pregunta, $id);
                        $ids[]=$pregunta['id'];
                    }
                    if($pregunta['almacenar']==1){
                        self::insertPreguntaAlmacen($pregunta, $idProfesor);
                    }
                }
                $queryDelete="DELETE FROM Examenes_Preguntas WHERE id NOT IN(".implode(",", $ids).") AND idExamen=?;";
                $stmt = self::$conexion->prepare($queryDelete);
                $stmt->bind_param("i",$id);
                $response=$stmt->execute();
            }
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
            exit;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
        
    }
    
    public static function insertExamenPregunta($data,$idExamen=0){
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "INSERT INTO Examenes_Preguntas (idExamen,enunciado,tipo,opciones) "
                . "VALUES (?,?,?,?)";
        $enunciado = $data['enunciado'];
        $tipo = $data['tipo'];
        $opciones = json_encode($data['opciones']);
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("isis",$idExamen,$enunciado,$tipo,$opciones);
            if ($stmt->execute()) { 
                $response = self::$conexion->affected_rows>0;
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
    public static function UpdateExamenPregunta($data,$idExamen=0){
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "UPDATE Examenes_Preguntas "
                . "enunciado=?,tipo=?,opciones=? "
                . "WHERE id=? AND idExamen=?";
        $id = $data['id'];
        $enunciado = $data['enunciado'];
        $tipo = $data['tipo'];
        $opciones = json_encode($data['opciones']);
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("sisii",$enunciado,$tipo,$opciones,$id,$idExamen);
            if ($stmt->execute()) { 
                $response = self::$conexion->affected_rows>0;
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
                        "enunciado"=>trim($pregunta["enunciado"]),
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
    
    public static function getPreguntasAlmacenByProfesor($idProfesor) {
        $estabaAbierta=self::isAbierta();
        $response = []; 
        $query = "SELECT * "
                . "FROM Examenes_Preguntas_Almacen p "
                . "WHERE idProfesor=? AND habilitado=1;";
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("i",$idProfesor);
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while($pregunta = $resultado->fetch_assoc()){
                    $response[]=[
                        "id"=>$pregunta["id"],
                        "enunciado"=>trim($pregunta["enunciado"]),
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

    public static function getPreguntaById($id) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $query = "SELECT * "
                . "FROM Examenes_Preguntas_Almacen "
                . "WHERE id=?;";
        try {   
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("i",$id);
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if($pregunta = $resultado->fetch_assoc()){
                    $response=[
                        "id"=>$pregunta["id"],
                        "enunciado"=>trim($pregunta["enunciado"]),
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

    public static function insertPreguntaAlmacen($data, $idProfesor=0) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "INSERT INTO Examenes_Preguntas_Almacen "
                . "(idProfesor,enunciado,tipo,opciones) "
                . "VALUES (?,?,?,?)";
        try {   
            $enunciado = $data['enunciado'];
            $tipo= $data['tipo'];
            $opciones=json_encode($data['opciones']);
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("isis",$idProfesor,$enunciado,$tipo,$opciones);
            $response = $stmt->execute();
            $stmt->close();
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();   
            $response = false;
        } finally {
            if(!$estabaAbierta) self::cerrarConexion();
            return $response;
        }
    }

    public static function updatePreguntaAlmacen($id,$data, $idProfesor=0) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $queryInsert = "UPDATE Examenes_Preguntas_Almacen SET "
                . "enunciado=?,tipo=?,opciones=? "
                . "WHERE id=? AND idProfesor=?";
        try {   
            $enunciado = $data['enunciado'];
            $tipo= $data['tipo'];
            $opciones=json_encode($data['opciones']);
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($queryInsert);
            $stmt->bind_param("sisii",$enunciado,$tipo,$opciones,$id,$idProfesor);
            if($stmt->execute()){
                $response = self::$conexion->affected_rows==1;
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

    public static function deletePreguntaAlmacen($id, $idProfesor=0) {
        $estabaAbierta=self::isAbierta();
        $response = false; 
        $query= "UPDATE Examenes_Preguntas_Almacen SET "
                . "habilitado=0 "
                . "WHERE id=? AND idProfesor=?;";
        try {
            if(!$estabaAbierta) self::abrirConexion();
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ii",$id,$idProfesor);
            if($stmt->execute()){
                $response = self::$conexion->affected_rows==1;
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
    
    /**
     * Función que guarda las respuestas de un examen hecho por un alumno
     * @param int $idAlumno Id del alumno que realiza el examen
     * @param int $idExamen Id del examen que realiza el alumno
     * @param arr[] $respuestas Respuestas del alumno
     */
    public static function saveRespuestasAlumno($idAlumno,$idExamen,$respuestas) {
        $estabaAbierta=self::isAbierta();
        try {
            if(!$estabaAbierta) self::abrirConexion();
            $query = "INSERT INTO Alumno_examen_respuestas (idAlumno, idExamen,idPregunta, respuesta) VALUES ";        
            $aux = [];
            foreach ($respuestas as $index=>$respuesta) {
                if(is_array($respuesta)) {                
                    $aux[]= "(".$idAlumno.","
                            . "".$idExamen.","
                            . "".$index.","
                            . "'".implode(',', $respuesta)."')";
                } else {
                    $aux[]= "(".$idAlumno.","
                            . "".$idExamen.","
                            . "".$index.","
                            . "'".$respuesta."')";                    
                }            
                                
            }
            $query .= implode(',', $aux).';';
            $query2 = "UPDATE Alumnos_examenes SET realizado=0 "
                    . "WHERE idExamen=".$idExamen." AND idAlumno=".$idAlumno.";";
            echo $query.'<br>'.$query2;                        
            
            self::$conexion->query($query);
            
            self::$conexion->query($query2);
            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString(); 
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
        }
    }
    
    /**
     * Función que recupera las respuestas de un examen realizado por el alumno
     * @param int $idAlumno Id del alumno que revisa el examen
     * @param int $idExamen Id del examen a revisar
     * @return arr[]  Devuelve un array con las respuestas del examen a revisar
     */
    public static function getRespuestasAlumno($idAlumno, $idExamen) {
        $estabaAbierta=self::isAbierta();
        $respuestas = [];
        try {
            if(!$estabaAbierta) self::abrirConexion();
            $query = "SELECT * FROM Alumno_examen_respuestas "
                . "WHERE idAlumno=? AND idExamen=?";
            
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ii",$idAlumno,$idExamen);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $respuestas [$datos['idPregunta']]= $datos['respuesta'];
            }
                       
        } catch (Exception $ex) {
            echo $ex->getTraceAsString(); 
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
            return $respuestas;
        }
         
    }
    
    /**
     * Funcion que recupera el id de los alumnos con un examen de un profesor
     * @param int $idProfesor Id del profesor actual
     * @param int $idExamen Id del examen a buscar
     * @return arr[] Devuelve un array con los id de los alumnos
     */
    public static function getAlumnosExamen($idProfesor,$idExamen) {
        $estabaAbierta=self::isAbierta();
        $alumnos=[];
        try {
            if(!$estabaAbierta) self::abrirConexion();
            $query = "SELECT a.idAlumno FROM alumnos_examenes a "
                . "LEFT JOIN examenes e ON a.idExamen=e.id "
                . "WHERE e.idProfesor=? AND a.idExamen=? AND a.realizado = 0";
        
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ii",$idProfesor,$idExamen);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while($datos = $resultado->fetch_assoc()) {
                $alumnos []=$datos['idAlumno'];               
            }            
        } catch (Exception $ex) {
            echo $ex->getTraceAsString(); 
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
            return $alumnos;
        }
    }
    
    public static function setNotaExamen($idAlumno,$idExamen,$nota) {
        $estabaAbierta=self::isAbierta();
        try {
            if(!$estabaAbierta) self::abrirConexion();
            $query = "UPDATE Alumnos_examenes SET nota=".$nota." "
                    . "WHERE idExamen=".$idExamen." AND idAlumno=".$idAlumno.";";
            self::$conexion->query($query);
        } catch (Exception $ex) {
            echo $ex->getTraceAsString(); 
        } finally {
            if(!$estabaAbierta) {
                self::cerrarConexion();
            }
        }    
    }
}
