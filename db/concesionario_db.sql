-- ============================================================
-- BASE DE DATOS: concesionario_db
-- Proyecto: Concesionaria de Autos
-- ------------------------------------------------------------
-- Archivo listo para importar en phpMyAdmin.
-- Primero borra las tablas si ya existen, después las crea
-- y por último carga los datos de prueba.
--
-- IMPORTANTE: el orden importa por las claves foráneas (FK).
--   - Para BORRAR: primero las tablas hijas (ventas), después las padres.
--   - Para CREAR/INSERTAR: primero las padres (usuarios, autos, clientes),
--     después la hija (ventas) que las referencia.
-- ============================================================

-- ------------------------------------------------------------
-- Borrado de tablas (en orden inverso a las dependencias)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS autos;
DROP TABLE IF EXISTS usuarios;

-- ------------------------------------------------------------
-- Tabla de usuarios (los que inician sesión en el sistema)
-- Roles posibles: admin, vendedor_baja, vendedor_media, vendedor_alta
-- NO existe el rol "cliente": los clientes son una entidad aparte.
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'vendedor_baja', 'vendedor_media', 'vendedor_alta') NOT NULL
);

-- ------------------------------------------------------------
-- Tabla de autos.
-- La columna "gama" decide qué vendedor puede ver el auto.
-- ------------------------------------------------------------
CREATE TABLE autos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    anio INT NOT NULL,
    precio DECIMAL(12,2) NOT NULL,
    color VARCHAR(30),
    kilometraje INT DEFAULT 0,
    gama ENUM('baja', 'media', 'alta') NOT NULL,
    estado ENUM('disponible', 'reservado', 'vendido') DEFAULT 'disponible'
);

-- ------------------------------------------------------------
-- Tabla de clientes (no inician sesión, solo se gestionan).
-- ------------------------------------------------------------
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100)
);

-- ------------------------------------------------------------
-- Tabla de ventas: vincula un cliente con un auto.
-- Guarda también qué usuario del sistema la registró.
-- ------------------------------------------------------------
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_auto INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('interesado', 'reservado', 'vendido') DEFAULT 'interesado',
    observaciones TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    FOREIGN KEY (id_auto) REFERENCES autos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- ============================================================
-- DATOS DE PRUEBA
-- ============================================================

-- Usuarios. La contraseña de todos es "1234" guardada con MD5.
-- Ejemplo: MD5('1234') → 'c4ca4238a0b923820dcc509a6f75849b'
INSERT INTO usuarios (nombre, apellido, email, password, rol) VALUES
('Carlos', 'Gomez', 'admin@concesionario.com', MD5('1234'), 'admin'),
('Laura', 'Martinez', 'vendedor1@concesionario.com', MD5('1234'), 'vendedor_baja'),
('Diego', 'Fernandez', 'vendedor2@concesionario.com', MD5('1234'), 'vendedor_media'),
('Sofia', 'Torres', 'vendedor3@concesionario.com', MD5('1234'), 'vendedor_alta');

-- Autos repartidos en las tres gamas (baja, media, alta).
INSERT INTO autos (marca, modelo, anio, precio, color, kilometraje, gama, estado) VALUES
('Chevrolet', 'Onix', 2022, 12000000, 'Blanco', 0, 'baja', 'disponible'),
('Renault', 'Sandero', 2021, 11000000, 'Rojo', 15000, 'baja', 'disponible'),
('Toyota', 'Corolla', 2023, 22000000, 'Negro', 0, 'media', 'disponible'),
('Volkswagen', 'Golf', 2022, 25000000, 'Gris', 5000, 'media', 'disponible'),
('BMW', 'Serie 3', 2023, 55000000, 'Blanco', 0, 'alta', 'disponible'),
('Mercedes', 'Clase C', 2023, 60000000, 'Negro', 0, 'alta', 'disponible');

-- Clientes de prueba.
INSERT INTO clientes (nombre, apellido, telefono, email) VALUES
('Juan', 'Perez', '351-1234567', 'juan@mail.com'),
('Maria', 'Lopez', '351-7654321', 'maria@mail.com');
