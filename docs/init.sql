/**
Inicializa la base de datos necesaria para la aplicación Mamas 2.0
*/
CREATE DATABASE IF NOT EXISTS Mamas
    CHARACTER SET utf8
    COLLATE utf8_spanish_ci;
    
USE Mamas;

CREATE TABLE `Roles` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO Roles (nombre,descripcion) VALUES 
('Administrador','Permisos totales sobre la aplicaciòn'),
('Profesor','Permisos para crear examenes y corregir'),
('Alumno','Permisos para realizar examenes y consultar notas');

CREATE TABLE `Usuarios` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellidos` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaNacimiento` date,
  `email` varchar(500)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `Usuarios_Roles` (
    `idUsuario` int NOT NULL,
    `idRol` int NOT NULL,
    
    PRIMARY KEY (idUsuario,idRol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `Examenes` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `habilitado` TINYINT NOT NULL DEFAULT '1' ,
    `activo` TINYINT NOT NULL DEFAULT '0' ,
    `idProfesor` INT NOT NULL ,
    `nombre` VARCHAR(500) NOT NULL,
    `descripcion` VARCHAR(1000), 
    `fechaInicio` TIMESTAMP , 
    `fechaFin` TIMESTAMP ,    
    PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE `Examenes_Preguntas` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `idExamen` int NOT NULL ,
  `enunciado` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo` tinyint DEFAULT 0 NOT NULL,
  `opciones` text

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `Examenes_Preguntas_Almacen` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `idProfesor` int NOT NULL,
  `enunciado` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo` tinyint DEFAULT '1',
  `opciones` text CHARACTER SET utf8 COLLATE utf8_spanish_ci

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `Alumnos_examenes` ( 
    `idAlumno` INT NOT NULL , 
    `idExamen` INT NOT NULL , 
    `nota` FLOAT(4,2) DEFAULT NULL , 
    `realizado` INT NOT NULL DEFAULT '0' , 
    PRIMARY KEY (`idAlumno`, `idExamen`)

) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE `Alumno_examen_respuestas` ( 
    `idAlumno` INT NOT NULL , 
    `idExamen` INT NOT NULL , 
    `idPregunta` INT NOT NULL , 
    `respuesta` TEXT NULL DEFAULT '' , 
    PRIMARY KEY (`idAlumno`, `idExamen`, `idPregunta`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;
