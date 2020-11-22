CREATE TABLE `Mamas`.`Alumnos_examenes` ( 
    `idAlumno` INT NOT NULL , 
    `idExamen` INT NOT NULL ,
    `nota` INT NOT NULL DEFAULT '1', 
    `realizado` INT NOT NULL DEFAULT '1',    
    PRIMARY KEY (`idAlumno`, `idExamen`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;