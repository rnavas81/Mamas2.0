USE Mamas;
CREATE TABLE `Roles` (
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO Roles (nombre,descripcion) VALUES 
('Administrador','Permisos totales sobre la aplicaciòn'),
('Profesor','Permisos para crear examenes y corregir'),
('Alumno','Permisos para realizar examenes y consultar notas');

CREATE TABLE `Usuarios` (
  `habilitado` tinyint NOT NULL DEFAULT '1',
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
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