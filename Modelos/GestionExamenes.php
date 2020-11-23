<?php
/**
 * Description of GestionUsuarios
 *
 * @author dario
 */
require_once '../configuracion.php';
require_once 'GestionDatos.php';
require_once 'Usuario.php';

class GestionExamenes extends GestionDatos {
    /**
     * Crea un objeto Usuario en base a datos
     * @param Array $datos
     * $datos[roles] debe ser un string con los idRol separado por comas
     */
    public static function formarExamen($datos=[]){
        $examen=[];
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

}
