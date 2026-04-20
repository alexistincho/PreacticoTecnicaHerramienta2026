CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (nombre) VALUES 
('Editor'),
('Validador');


CREATE TABLE usuario_roles (
    usuario_id INT,
    rol_id INT,
    PRIMARY KEY (usuario_id, rol_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);


CREATE TABLE estados_noticia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO estados_noticia (nombre) VALUES
('Borrador'),
('Lista para Validación'),
('Para Corrección'),
('Publicada'),
('Expirada'),
('Anulada');


CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_publicacion DATETIME NULL,
    imagen VARCHAR(255),
    estado_id INT NOT NULL,
    autor_id INT NOT NULL,

    FOREIGN KEY (estado_id) REFERENCES estados_noticia(id),
    FOREIGN KEY (autor_id) REFERENCES usuarios(id)
);


CREATE INDEX idx_estado ON noticias(estado_id);
CREATE INDEX idx_autor ON noticias(autor_id);


CREATE TABLE historial_noticia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    usuario_id INT NOT NULL,
    estado_anterior_id INT,
    estado_nuevo_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,

    FOREIGN KEY (noticia_id) REFERENCES noticias(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_anterior_id) REFERENCES estados_noticia(id),
    FOREIGN KEY (estado_nuevo_id) REFERENCES estados_noticia(id)
);


CREATE TABLE parametros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE,
    valor VARCHAR(100)
);

INSERT INTO parametros (clave, valor) VALUES
('dias_expiracion', '30'),
('tamano_max_imagen_mb', '2');

-- ============================================
-- TABLA OPCIONAL: transiciones_estado
-- ============================================
CREATE TABLE transiciones_estado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_origen INT,
    estado_destino INT,

    FOREIGN KEY (estado_origen) REFERENCES estados_noticia(id),
    FOREIGN KEY (estado_destino) REFERENCES estados_noticia(id)
);

-- Transiciones permitidas
INSERT INTO transiciones_estado (estado_origen, estado_destino)
SELECT eo.id, ed.id
FROM estados_noticia eo, estados_noticia ed
WHERE 
(eo.nombre = 'Borrador' AND ed.nombre IN ('Lista para Validación','Anulada')) OR
(eo.nombre = 'Lista para Validación' AND ed.nombre IN ('Publicada','Para Corrección')) OR
(eo.nombre = 'Para Corrección' AND ed.nombre IN ('Borrador','Lista para Validación'));

-- ============================================
-- NOTA:
-- Algunas reglas (validaciones, expiración, roles)
-- deben implementarse en el backend (PHP)
-- ============================================
```
