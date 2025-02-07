CREATE DATABASE bd_netflix;
USE bd_netflix;

-- Tabla de Usuarios
CREATE TABLE usuarios (
  id INT(10) NOT NULL AUTO_INCREMENT,
  rol ENUM('cliente', 'admin') NOT NULL,
  nombre VARCHAR(20) NOT NULL,
  apellido VARCHAR(30) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  telefono CHAR(9) NOT NULL,
  estado ENUM('activo', 'inactivo', 'pendiente') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Carteleras (Películas)
CREATE TABLE carteleras (
  id INT(10) NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(40) NOT NULL,
  descripcion VARCHAR(500) NOT NULL,
  img VARCHAR(155) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Nueva tabla de Géneros
CREATE TABLE generos (
  id INT(10) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(30) NOT NULL UNIQUE,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Nueva tabla intermedia para relación N:M entre carteleras y géneros
CREATE TABLE cartelera_generos (
  id_cartelera INT(10) NOT NULL,
  id_genero INT(10) NOT NULL,
  PRIMARY KEY (id_cartelera, id_genero),
  FOREIGN KEY (id_cartelera) REFERENCES carteleras(id) ON DELETE CASCADE,
  FOREIGN KEY (id_genero) REFERENCES generos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Likes
CREATE TABLE likes (
  id INT(10) NOT NULL AUTO_INCREMENT,
  id_usuarios INT(11) NOT NULL,
  id_carteleras INT(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_usuarios) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (id_carteleras) REFERENCES carteleras(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;