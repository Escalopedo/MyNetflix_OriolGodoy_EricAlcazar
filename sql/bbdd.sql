CREATE DATABASE bd_netflix;
USE bd_netflix;

CREATE TABLE usuarios (
  id int(10) NOT NULL AUTO_INCREMENT,
  rol enum('cliente','admin') NOT NULL,
  nombre varchar(20) NOT NULL,
  apellido varchar(30) NOT NULL,
  correo varchar(100) NOT NULL UNIQUE,
  contrasena varchar(255) NOT NULL,
  telefono char(9) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE carteleras (
  id int(10) NOT NULL AUTO_INCREMENT,
  titulo varchar(40) NOT NULL,
  descripcion varchar(500) NOT NULL,
  img varchar(155) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE likes (
  id int(10) NOT NULL AUTO_INCREMENT,
  id_usuarios int(11) NOT NULL,
  id_carteleras int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY id_carteleras (id_carteleras),
  KEY id_usuarios (id_usuarios)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FKs

ALTER TABLE likes
  ADD CONSTRAINT fk_likes_usuarios
  FOREIGN KEY (id_usuarios) REFERENCES usuarios (id);

ALTER TABLE likes
  ADD CONSTRAINT fk_likes_carteleras
  FOREIGN KEY (id_carteleras) REFERENCES carteleras (id);
