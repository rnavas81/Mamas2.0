/**
 * Author:  rodrigo
 * Created: 17-nov-2020
 * Base de datos para el proyecto Mamas2.0
 */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS Mamas
    CHARACTER SET utf8
    COLLATE utf8_spanish_ci;

USE Mamas;


CREATE TABLE `Roles` (
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `Usuarios` (
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellidos` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaNacimiento` date,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

