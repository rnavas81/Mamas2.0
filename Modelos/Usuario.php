<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author rodrigo
 */
class Usuario {
    private $id;
    private $nombre;
    private $apellidos;
    private $dni;
    private $fechaNacimiento;
    private $email;
    private $roles;
    function __construct($id, $dni, $nombre="", $apellidos="", $fechaNacimiento=null, $email="",$roles=[]) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->dni = $dni;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->email = $email;
    }
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellidos() {
        return $this->apellidos;
    }

    function getDni() {
        return $this->dni;
    }

    function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    function getEmail() {
        return $this->email;
    }
    
    function getRoles() {
        return $this->roles;
    }
    
    function setId($id): void {
        $this->id = $id;
    }

    function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos): void {
        $this->apellidos = $apellidos;
    }

    function setDni($dni): void {
        $this->dni = $dni;
    }

    function setFechaNacimiento($fechaNacimiento): void {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    function setEmail($email): void {
        $this->email = $email;
    }

    function setRoles($roles): void {
        $this->roles = $roles;
    }

}
