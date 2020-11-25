<?php

/**
 * @author Rodrigo Navas
 * 
 * Variables de configuración
 */
const DS = DIRECTORY_SEPARATOR;
//----------------------- RUTAS WEB
const WEB_ROOT = DS . 'EjemplosPHP' . DS . 'Mamas2.0';
//CONTROLADORES
const WEB_CTRL = WEB_ROOT.DS.'Controladores';
const CTRL_BASICO = WEB_CTRL.DS.'basico.php';
const CTRL_USUARIOS = WEB_CTRL.DS.'usuarios.php';
const CTRL_ADMIN = WEB_CTRL.DS.'administrador.php';
const CTRL_EXAMENES = WEB_CTRL.DS.'examenes.php';
const CTRL_PROFESORES = WEB_CTRL.DS.'profesores.php';
const CTRL_ALUMNOS = WEB_CTRL.DS.'alumnos.php';
/*
  const CTRL_TAREAS = WEB_CTRL.DS.'tareas.php';
 */

/*
  //ESTILOS
  const WEB_CSS = WEB_ROOT.DS.'CSS';
  const CSS_GLOBAL = WEB_CSS.DS.'global.css';
  const CSS_STYLE = WEB_CSS.DS.'style.css';
 */
//VISTAS
const WEB_INDEX = WEB_ROOT . DS . 'index.php';
const WEB_ADMIN = WEB_ROOT . DS . 'admin.php';
const WEB_VISTAS = WEB_ROOT . DS . 'Vistas';
const WEB_ENTRADA_ALUMNOS = WEB_VISTAS . DS . 'entradaAlumnos.php';
const WEB_ENTRADA_PROFESORES = WEB_VISTAS . DS . 'entradaProfesores.php';
const WEB_ENTRADA_ADMINISTRADORES = WEB_VISTAS . DS . 'entradaAdministradores.php';
const WEB_RECUPERAR = WEB_VISTAS . DS . 'recuperar.php';
const WEB_REGISTRAR = WEB_VISTAS . DS . 'registrar.php';
const WEB_USUARIO_FORMULARIO = WEB_VISTAS . DS . 'usuarioFormulario.php';
const WEB_EXAMEN_FORMULARIO = WEB_VISTAS . DS . 'examenFormulario.php';
const WEB_EXAMEN_ALUMNO_REALIZA = WEB_VISTAS . DS . 'realizarExamen.php';

/*
  const WEB_ADMIN = WEB_ROOT.DS.'admin.php';
  const WEB_VALIDAR_USUARIO = WEB_ROOT.DS.'validar.php';
  const WEB_ENTRADA_ADMINISTRADOR = WEB_VISTAS.DS.'entradaAdministrador.php';
  const WEB_TAREA_FORMULARIO = WEB_VISTAS.DS.'TareaFormulario.php';
 */
//
const HASH_CODE = "Esta es nuestra clave de encriptacion";
