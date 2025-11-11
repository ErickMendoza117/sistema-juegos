-- ============================================
-- Base de Datos: Sistema de Gestión de Juegos
-- Compatible con: MySQL/MariaDB (WAMP/LAMP)
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_juegos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE sistema_juegos;

-- ============================================
-- Tabla: usuarios
-- Propósito: Almacenar usuarios para autenticación
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    activo TINYINT(1) DEFAULT 1,
    INDEX idx_nombre_usuario (nombre_usuario),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: juegos
-- Propósito: Almacenar información de juegos
-- ============================================
CREATE TABLE IF NOT EXISTS juegos (
    id_juego INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    genero VARCHAR(50) NOT NULL,
    plataforma VARCHAR(100) NOT NULL,
    desarrollador VARCHAR(100),
    fecha_lanzamiento DATE,
    precio DECIMAL(10, 2) DEFAULT 0.00,
    clasificacion VARCHAR(10),
    descripcion TEXT,
    stock INT DEFAULT 0,
    imagen_url VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_genero (genero),
    INDEX idx_plataforma (plataforma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Datos de prueba: Usuarios
-- Nota: Las contraseñas están en texto plano solo para pruebas
-- En producción, usar password_hash() de PHP
-- ============================================
INSERT INTO usuarios (nombre_usuario, contrasena, nombre_completo, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin@juegos.com'),
('usuario1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', 'juan@email.com'),
('usuario2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'maria@email.com');

-- Contraseña para todos los usuarios de prueba: "password"
-- Generar hash en PHP: password_hash("password", PASSWORD_DEFAULT)

-- ============================================
-- Datos de prueba: Juegos
-- ============================================
INSERT INTO juegos (titulo, genero, plataforma, desarrollador, fecha_lanzamiento, precio, clasificacion, descripcion, stock) VALUES
('The Legend of Zelda: Breath of the Wild', 'Aventura', 'Nintendo Switch', 'Nintendo', '2017-03-03', 59.99, 'E10+', 'Juego de aventura y exploración en mundo abierto', 15),
('God of War', 'Acción', 'PlayStation 5', 'Santa Monica Studio', '2018-04-20', 49.99, 'M', 'Aventura de acción basada en la mitología nórdica', 20),
('Minecraft', 'Sandbox', 'PC, Consolas, Móvil', 'Mojang Studios', '2011-11-18', 26.95, 'E10+', 'Juego de construcción y supervivencia en mundo abierto', 50),
('Elden Ring', 'RPG', 'PC, PlayStation, Xbox', 'FromSoftware', '2022-02-25', 59.99, 'M', 'RPG de acción en mundo abierto', 25),
('FIFA 23', 'Deportes', 'PC, PlayStation, Xbox', 'EA Sports', '2022-09-30', 69.99, 'E', 'Simulador de fútbol', 30),
('Call of Duty: Modern Warfare II', 'Shooter', 'PC, PlayStation, Xbox', 'Infinity Ward', '2022-10-28', 69.99, 'M', 'Shooter en primera persona', 18),
('Animal Crossing: New Horizons', 'Simulación', 'Nintendo Switch', 'Nintendo', '2020-03-20', 59.99, 'E', 'Simulador de vida en una isla', 22),
('Cyberpunk 2077', 'RPG', 'PC, PlayStation, Xbox', 'CD Projekt Red', '2020-12-10', 49.99, 'M', 'RPG de acción en mundo abierto futurista', 12),
('Super Mario Odyssey', 'Plataformas', 'Nintendo Switch', 'Nintendo', '2017-10-27', 59.99, 'E10+', 'Juego de plataformas 3D', 28),
('Red Dead Redemption 2', 'Acción-Aventura', 'PC, PlayStation, Xbox', 'Rockstar Games', '2018-10-26', 59.99, 'M', 'Aventura del viejo oeste en mundo abierto', 16);

-- ============================================
-- Consultas útiles para verificación
-- ============================================

-- Ver todos los usuarios
-- SELECT * FROM usuarios;

-- Ver todos los juegos
-- SELECT * FROM juegos;

-- Buscar juegos por género
-- SELECT * FROM juegos WHERE genero = 'RPG';

-- Buscar juegos por plataforma
-- SELECT * FROM juegos WHERE plataforma LIKE '%PlayStation%';

-- Contar juegos por género
-- SELECT genero, COUNT(*) as total FROM juegos GROUP BY genero;

-- ============================================
-- Notas importantes:
-- ============================================
-- 1. Para WAMP: Importar este archivo desde phpMyAdmin
-- 2. Para AWS EC2 (LAMP): Usar comando: mysql -u root -p < database.sql
-- 3. Actualizar credenciales de conexión en PHP según el entorno
-- 4. Las contraseñas de prueba están hasheadas con bcrypt
-- 5. Crear usuario MySQL específico para la aplicación en producción:
--    CREATE USER 'juegos_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
--    GRANT ALL PRIVILEGES ON sistema_juegos.* TO 'juegos_user'@'localhost';
--    FLUSH PRIVILEGES;
-- ============================================
