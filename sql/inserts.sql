

INSERT INTO directores (nombre, fecha_nacimiento, descripcion, img) VALUES
  ('Christopher Nolan', 1970, 'Director británico conocido por películas como la trilogía de Batman y Inception.', 'nolan.jpeg'),
  ('Zack Snyder', 1966, 'Director de películas de acción y superhéroes como 300 y Batman v Superman.', 'snyder.jpg'),
  ('Matt Reeves', 1966, 'Director estadounidense conocido por dirigir The Batman y la saga El planeta de los simios.', 'reeves.jpg'),
  ('Peter Jackson', 1961, 'Director neozelandés famoso por la trilogía de El Señor de los Anillos.', 'jackson.jpg'),
  ('David Yates', 1963, 'Director británico que dirigió varias películas de Harry Potter.', 'yates.jpg'),
  ('Jon Favreau', 1966, 'Director y actor estadounidense, director de El Rey León (2019) y Iron Man.', 'favreau.jpg'),
  ('James Cameron', 1954, 'Director canadiense de grandes éxitos como Titanic y Avatar.', 'cameron.jpg'),
  ('Steven Spielberg', 1946, 'Director legendario de películas como Jurassic Park y Tiburón.', 'spielberg.jpg'),
  ('Anthony Russo y Joe Russo', 1970, 'Hermanos directores de varias películas de Marvel, incluyendo Infinity War.', 'russo.jpg'),
  ('John Lasseter', 1957, 'Animador y director estadounidense, clave en el éxito de Pixar.', 'lasseter.jpg'),
  ('Brad Bird', 1957, 'Director de Los Increíbles y Ratatouille.', 'bird.jpg'),
  ('Damien Chazelle', 1985, 'Director de Whiplash y La La Land.', 'chazelle.jpg'),
  ('Chris Wedge', 1957, 'Director de Robots y fundador de Blue Sky Studios.', 'wedge.jpg'),
  ('Lana Wachowski y Lilly Wachowski', 1965, 'Hermanas directoras de la saga de The Matrix.', 'wachowski.jpg'),
  ('Lee Unkrich', 1967, 'Director de Coco y editor en Toy Story.', 'unkrich.jpg'),
  ('James Wan', 1977, 'Director de El Conjuro y creador de la saga Saw.', 'wan.jpg'),
  ('Christopher Nolan', 1970, 'Director británico de películas como Interstellar y Dunkirk.', 'nolan.jpg');


INSERT INTO generos (nombre) VALUES
  ('Acción'),
  ('Aventura'),
  ('Crimen'),
  ('Drama'),
  ('Superhéroes'),
  ('Suspenso'),
  ('Familia'),
  ('Musical'),
  ('Terror'),
  ('Animación'),
  ('Ciencia Ficción'),
  ('Fantasía'),
  ('Biografía'),
  ('Misterio');

INSERT INTO usuarios (rol, nombre, apellido, correo, contrasena, telefono, estado) VALUES
  ('admin', 'Eric', 'Alcazar', 'eric@gmail.com', '$2y$10$JKf/1XySKIXHodgLZi4rpOVYR8NnBLVVKXeWr9WDKhTdVxP3q3DnS', '611475124', 'activo'),
  ('admin', 'Ori', 'Godoy', 'ori@gmail.com', '$2y$10$JKf/1XySKIXHodgLZi4rpOVYR8NnBLVVKXeWr9WDKhTdVxP3q3DnS', '619421222', 'activo'),
  ('cliente', 'Alberto', 'DeSantos', 'alberto@gmail.com', '$2y$10$JKf/1XySKIXHodgLZi4rpOVYR8NnBLVVKXeWr9WDKhTdVxP3q3DnS', '600000001', 'activo'),
  ('cliente', 'Fatima', 'Martinez', 'fatima@gmail.com', '$2y$10$JKf/1XySKIXHodgLZi4rpOVYR8NnBLVVKXeWr9WDKhTdVxP3q3DnS', '600000002', 'activo'),
  ('cliente', 'Agnes', 'Plans', 'agnes@gmail.com', '$2y$10$JKf/1XySKIXHodgLZi4rpOVYR8NnBLVVKXeWr9WDKhTdVxP3q3DnS', '600000003', 'activo');
  
INSERT INTO carteleras (titulo, descripcion, img, id_director) VALUES
  ('Batman Begins', 'El origen de Batman, donde Bruce Wayne se convierte en el Caballero Oscuro para luchar contra el crimen en Gotham City.', 'batman_begins.jpg', 1),
  ('The Dark Knight', 'Batman enfrenta al Joker, un criminal psicópata que busca sumergir a Gotham City en el caos.', 'the_dark_knight.jpg', 1),
  ('The Dark Knight Rises', 'Después de una larga ausencia, Batman regresa para enfrentarse a Bane, un terrorista que planea destruir Gotham City.', 'the_dark_knight_rises.jpg', 1),
  ('Batman v Superman: Dawn of Justice', 'Batman se enfrenta a Superman, creyendo que es una amenaza para la humanidad.', 'batman_v_superman.jpg', 2),
  ('The Batman', 'Un joven Bruce Wayne investiga una serie de crímenes en Gotham mientras enfrenta a Enigma y otros villanos.', 'the_batman.jpg', 3),
  ('El Señor de los Anillos: La Comunidad del Anillo', 'Un joven hobbit llamado Frodo debe destruir un anillo mágico que posee un gran poder para evitar que caiga en manos malvadas.', 'señor_anillos_1.jpg', 4),
  ('Harry Potter y la Piedra Filosofal', 'Un joven llamado Harry Potter descubre que es un mago y entra en una escuela mágica para aprender y enfrentar sus propios retos.', 'harry_potter_1.jpg', 5),
  ('El Rey León', 'Un joven león llamado Simba debe aprender a ser rey tras la muerte de su padre, enfrentando varios desafíos en su camino.', 'rey_leon.jpg', 6),
  ('Avatar', 'Un parapléjico es enviado a Pandora, un planeta lejano, para infiltrarse entre los nativos, pero termina cambiando su lealtad y ayudando a los nativos a luchar contra los humanos.', 'avatar.jpg', 7),
  ('Jurassic Park', 'Un parque temático con dinosaurios clonados se convierte en un peligro mortal cuando los animales escapan y comienzan a atacar a los humanos.', 'jurassic_park.jpg', 8),
  ('Vengadores: Infinity War', 'Los Vengadores deben unirse para luchar contra Thanos, un ser que busca recoger todas las gemas del infinito para destruir la mitad del universo.', 'avengers_infinity_war.jpg', 9),
  ('Toy Story', 'Un grupo de juguetes que cobran vida cuando los humanos no los ven enfrentan varios desafíos mientras un nuevo juguete, Buzz Lightyear, llega al grupo.', 'toy_story.jpg', 10),
  ('Los Increíbles', 'Una familia de superhéroes intenta llevar una vida normal mientras luchan contra un villano que pone en peligro al mundo.', 'increibles.jpg', 11),
  ('Tiburón', 'Un monstruoso tiburón asesino atemoriza a los habitantes de una pequeña isla, mientras un grupo de hombres intenta cazarlo.', 'tiburon.jpg', 8),
  ('La La Land', 'Una joven aspirante a actriz y un pianista se enamoran mientras persiguen sus sueños en Los Ángeles.', 'la_la_land.jpg', 12),
  ('Robots', 'En un mundo habitado solo por robots, un joven inventor llamado Rodney busca una manera de mejorar su vida y la de sus amigos.', 'robots.jpg', 13),
  ('Matrix', 'Un hacker descubre que la realidad es una simulación creada por máquinas y se une a una rebelión para liberar a la humanidad.', 'matrix.jpg', 14),
  ('Coco', 'Un joven músico llamado Miguel viaja al mundo de los muertos para descubrir la historia de su familia y su conexión con la música.', 'coco.jpg', 15),
  ('El Conjuro', 'Una pareja de investigadores paranormales ayuda a una familia aterrorizada por una presencia maligna en su hogar.', 'el_conjuro.jpg', 16),
  ('Interestelar', 'Un grupo de astronautas viaja a través de un agujero de gusano en busca de un nuevo hogar para la humanidad.', 'interestelar.jpg', 17);


-- Relacionar películas con géneros
INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES
  (1, 1), (1, 2), (1, 5),
  (2, 1), (2, 3), (2, 6),
  (3, 1), (3, 4), (3, 5),
  (4, 1), (4, 5), (4, 4),
  (5, 3), (5, 6), (5, 4),
  (6, 7), (6, 2), (6, 8),
  (7, 2), (7, 5), (7, 4),
  (8, 2), (8, 5), (8, 9),
  (9, 7), (9, 2), (9, 4),
  (10, 8), (10, 2), (10, 1),
  (11, 8), (11, 2), (11, 1),
  (12, 1), (12, 2), (12, 8),
  (13, 7), (13, 2), (13, 9),
  (14, 7), (14, 1), (14, 9),
  (15, 10), (15, 6), (15, 1),
  (16, 6), (16, 11), (16, 4),
  (17, 1), (17, 10), (17, 11),
  (18, 7), (18, 8), (18, 12),
  (19, 9), (19, 6), (19, 13),
  (20, 10), (20, 11), (20, 4);