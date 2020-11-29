<?php
/**
 * Description of examen
 *
 * @author dario
 */
class Examen {
    private $id;
    private $idProfesor;
    private $nombre;
    private $descripcion;
    private $fechaInicio;
    private $fechaFin;    
    private $habilitado;
    private $activo;
    private $nota;
            
    function __construct($id, $idProfesor, $nombre, $descripcion, $fechaInicio, $fechaFin, $habilitado, $activo, $nota="") {
        $this->id = $id;
        $this->idProfesor = $idProfesor;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->habilitado = $habilitado;
        $this->activo = $activo;
        $this->nota = $nota;
    }

    function getId() {
        return $this->id;
    }

    function getIdProfesor() {
        return $this->idProfesor;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFechaInicio() {
        return $this->fechaInicio;
    }

    function getFechaFin() {
        return $this->fechaFin;
    }

    function getHabilitado() {
        return $this->habilitado;
    }

    function getActivo() {
        return $this->activo;
    }

    function getNota() {
        return $this->nota;
    }

    function setId($id): void {
        $this->id = $id;
    }

    function setIdProfesor($idProfesor): void {
        $this->idProfesor = $idProfesor;
    }

    function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    function setFechaInicio($fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }

    function setFechaFin($fechaFin): void {
        $this->fechaFin = $fechaFin;
    }

    function setHabilitado($habilitado): void {
        $this->habilitado = $habilitado;
    }

    function setActivo($activo): void {
        $this->activo = $activo;
    }

    function setNota($nota): void {
        $this->nota = $nota;
    }

}
