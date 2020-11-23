CREATE TABLE `Mamas`.`Examenes` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `idProfesor` INT NOT NULL ,
    `habilitado` TINYINT NOT NULL DEFAULT '1' ,
    `activo` TINYINT NOT NULL DEFAULT '0' ,
    `nombre` VARCHAR(500) NOT NULL,
    `descripcion` VARCHAR(1000), 
    `fechaInicio` TIMESTAMP , 
    `fechaFin` TIMESTAMP ,    
    PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;