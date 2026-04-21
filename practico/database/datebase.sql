CREATE TABLE usuarios (
    id_usuario INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    contrasenia VARCHAR(255) NOT NULL,
    INDEX (email)
);

CREATE TABLE rol (
    id_rol INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE usuario_roles (
    id_usuario INT(11) NOT NULL,
    id_rol INT(11) NOT NULL,
    PRIMARY KEY (id_usuario, id_rol),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

CREATE TABLE noticias (
    id_noticia INT(11) AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_publicacion DATETIME DEFAULT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    estado VARCHAR(50) NOT NULL DEFAULT 'Borrador',
    id_autor INT(11) DEFAULT NULL,
    INDEX (id_autor),
    FOREIGN KEY (id_autor) REFERENCES usuarios(id_usuario)
);

CREATE TABLE historial (
    id_historial INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_noticia INT(11) DEFAULT NULL,
    id_usuario INT(11) DEFAULT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_anterior VARCHAR(50) DEFAULT NULL,
    estado_nuevo VARCHAR(50) DEFAULT NULL,
    INDEX (id_noticia),
    INDEX (id_usuario),
    FOREIGN KEY (id_noticia) REFERENCES noticias(id_noticia),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE parametros (
    id_parametro INT(11) AUTO_INCREMENT PRIMARY KEY,
    dias_expiracion INT(11) NOT NULL
);


/* ----------consulta solo para rol --------------*/

INSERT INTO rol (id_rol, nombre) VALUES
(1, 'editor'),
(2, 'validador');