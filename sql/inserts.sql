
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
  
INSERT INTO carteleras (titulo, descripcion, img) VALUES
  ('Batman Begins', 'El origen de Batman, donde Bruce Wayne se convierte en el Caballero Oscuro para luchar contra el crimen en Gotham City.', 'batman_begins.jpg'),
  ('The Dark Knight', 'Batman enfrenta al Joker, un criminal psicópata que busca sumergir a Gotham City en el caos.', 'the_dark_knight.jpg'),
  ('The Dark Knight Rises', 'Después de una larga ausencia, Batman regresa para enfrentarse a Bane, un terrorista que planea destruir Gotham City.', 'the_dark_knight_rises.jpg'),
  ('Batman v Superman: Dawn of Justice', 'Batman se enfrenta a Superman, creyendo que es una amenaza para la humanidad, mientras se prepara la llegada de nuevos héroes.', 'batman_v_superman.jpg'),
  ('The Batman', 'Un joven Bruce Wayne investiga una serie de crímenes en Gotham mientras enfrenta a Enigma y otros villanos.', 'the_batman.jpg'),
  ('El Señor de los Anillos: La Comunidad del Anillo', 'Un joven hobbit llamado Frodo debe destruir un anillo mágico que posee un gran poder para evitar que caiga en manos malvadas.', 'señor_anillos_1.jpg'),
  ('Harry Potter y la Piedra Filosofal', 'Un joven llamado Harry Potter descubre que es un mago y entra en una escuela mágica para aprender y enfrentar sus propios retos.', 'harry_potter_1.jpg'),
  ('El Rey León', 'Un joven león llamado Simba debe aprender a ser rey tras la muerte de su padre, enfrentando varios desafíos en su camino.', 'rey_leon.jpg'),
  ('Avatar', 'Un parapléjico es enviado a Pandora, un planeta lejano, para infiltrarse entre los nativos, pero termina cambiando su lealtad y ayudando a los nativos a luchar contra los humanos.', 'avatar.jpg'),
  ('Jurassic Park', 'Un parque temático con dinosaurios clonados se convierte en un peligro mortal cuando los animales escapan y comienzan a atacar a los humanos.', 'jurassic_park.jpg'),
  ('Vengadores: Infinity War', 'Los Vengadores deben unirse para luchar contra Thanos, un ser que busca recoger todas las gemas del infinito para destruir la mitad del universo.', 'avengers_infinity_war.jpg'),
  ('Toy Story', 'Un grupo de juguetes que cobran vida cuando los humanos no los ven, enfrentan varios desafíos mientras un nuevo juguete, Buzz Lightyear, llega al grupo.', 'toy_story.jpg'),
  ('Los Increíbles', 'Una familia de superhéroes intenta llevar una vida normal mientras luchan contra un villano que pone en peligro al mundo.', 'increibles.jpg'),
  ('Tiburón', 'Un monstruoso tiburón asesino atemoriza a los habitantes de una pequeña isla, mientras un grupo de hombres intenta cazarlo.', 'tiburon.jpg'),
  ('La La Land', 'Una joven aspirante a actriz y un pianista se enamoran mientras persiguen sus sueños en Los Ángeles, pero su relación enfrenta obstáculos mientras tratan de lograr sus metas profesionales.', 'la_la_land.jpg'),
  ('Robots', 'En un mundo habitado solo por robots, un joven inventor llamado Rodney busca una manera de mejorar su vida y la de sus amigos, enfrentándose a una poderosa corporación que intenta controlar el mundo.', 'robots.jpg'),
  ('Matrix', 'Un hacker descubre que la realidad es una simulación creada por máquinas y se une a una rebelión para liberar a la humanidad.', 'matrix.jpg'),
  ('Coco', 'Un joven músico llamado Miguel viaja al mundo de los muertos para descubrir la historia de su familia y su conexión con la música.', 'coco.jpg'),
  ('El Conjuro', 'Una pareja de investigadores paranormales ayuda a una familia aterrorizada por una presencia maligna en su hogar.', 'el_conjuro.jpg'),
  ('Interestelar', 'Un grupo de astronautas viaja a través de un agujero de gusano en busca de un nuevo hogar para la humanidad.', 'interestelar.jpg');

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