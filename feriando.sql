-- ============================================================
-- FERIANDO - BASE DE DATOS COMPLETA
-- ------------------------------------------------------------
-- Este archivo se puede importar las veces que quieras.
-- No borra datos existentes y agrega las columnas nuevas si
-- la base ya existía (migración automática).
--
-- Importar desde phpMyAdmin: pestaña "Importar" -> feriando.sql
-- o por consola: mysql -u root -p < feriando.sql
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS feriando
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE feriando;

-- ------------------------------------------------------------
-- 0. Roles
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS roles (
    id_rol INT NOT NULL PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (id_rol, nombre) VALUES
    (1, 'consumidor'),
    (2, 'emprendedor'),
    (3, 'feria')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- ------------------------------------------------------------
-- 1. Usuarios (nombre, email y contraseña cifrada)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL DEFAULT 1,
    telefono VARCHAR(30) NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email),
    KEY idx_usuarios_rol (id_rol),
    CONSTRAINT fk_usuarios_rol FOREIGN KEY (id_rol)
        REFERENCES roles (id_rol) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. Perfiles de emprendedor
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Perfiles_Emprendedor (
    id_emprendedor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre_marca VARCHAR(100) NOT NULL,
    categoria_producto VARCHAR(50) NULL,
    descripcion_emprendimiento TEXT NULL,
    redes_sociales TEXT NULL,
    instagram VARCHAR(100) NULL,
    foto_url VARCHAR(255) NULL,
    telefono VARCHAR(30) NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_perfil_usuario (id_usuario),
    CONSTRAINT fk_perfil_usuario FOREIGN KEY (id_usuario)
        REFERENCES Usuarios (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. Ferias
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Ferias (
    id_feria INT AUTO_INCREMENT PRIMARY KEY,
    id_organizador INT NOT NULL,
    nombre_feria VARCHAR(120) NOT NULL,
    descripcion TEXT NULL,
    direccion_texto VARCHAR(255) NOT NULL,
    latitud DECIMAL(9,6) NULL,
    longitud DECIMAL(9,6) NULL,
    categoria VARCHAR(50) NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'activa',
    imagen_url VARCHAR(255) NULL,
    instagram VARCHAR(100) NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_ferias_organizador (id_organizador),
    KEY idx_ferias_estado (estado),
    CONSTRAINT fk_ferias_organizador FOREIGN KEY (id_organizador)
        REFERENCES Usuarios (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. Fechas y horarios de cada feria
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Fechas_Feria (
    id_fecha INT AUTO_INCREMENT PRIMARY KEY,
    id_feria INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    KEY idx_fechas_feria (id_feria),
    KEY idx_fechas_inicio (fecha_inicio),
    CONSTRAINT fk_fechas_feria FOREIGN KEY (id_feria)
        REFERENCES Ferias (id_feria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. Puestos de emprendedores en cada feria
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Feria_Puestos (
    id_puesto INT AUTO_INCREMENT PRIMARY KEY,
    id_feria INT NOT NULL,
    id_emprendedor INT NOT NULL,
    numero_puesto VARCHAR(20) NULL,
    lugar VARCHAR(150) NULL,
    hora VARCHAR(30) NULL,
    estado_solicitud VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    KEY idx_puestos_feria (id_feria),
    KEY idx_puestos_emprendedor (id_emprendedor),
    CONSTRAINT fk_puestos_feria FOREIGN KEY (id_feria)
        REFERENCES Ferias (id_feria) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_puestos_emprendedor FOREIGN KEY (id_emprendedor)
        REFERENCES Perfiles_Emprendedor (id_emprendedor) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. Favoritos del consumidor
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_consumidor INT NOT NULL,
    id_feria INT NOT NULL,
    fecha_guardado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_favorito (id_consumidor, id_feria),
    KEY idx_favoritos_feria (id_feria),
    CONSTRAINT fk_favoritos_consumidor FOREIGN KEY (id_consumidor)
        REFERENCES Usuarios (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_favoritos_feria FOREIGN KEY (id_feria)
        REFERENCES Ferias (id_feria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. Reseñas / comentarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resenas (
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_consumidor INT NOT NULL,
    id_feria INT NULL,
    id_emprendedor INT NULL,
    calificacion INT NOT NULL,
    comentario TEXT NULL,
    fecha_resena TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_resenas_consumidor (id_consumidor),
    KEY idx_resenas_feria (id_feria),
    KEY idx_resenas_emprendedor (id_emprendedor),
    CONSTRAINT fk_resenas_consumidor FOREIGN KEY (id_consumidor)
        REFERENCES Usuarios (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_resenas_feria FOREIGN KEY (id_feria)
        REFERENCES Ferias (id_feria) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_resenas_emprendedor FOREIGN KEY (id_emprendedor)
        REFERENCES Perfiles_Emprendedor (id_emprendedor) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_resenas_calificacion CHECK (calificacion BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. Avisos de emprendedores
--    (incluye lugar, hora, puesto, Instagram y foto)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS Avisos (
    id_aviso INT AUTO_INCREMENT PRIMARY KEY,
    id_emprendedor INT NOT NULL,
    id_feria INT NULL,
    mensaje VARCHAR(255) NOT NULL,
    lugar VARCHAR(150) NULL,
    hora VARCHAR(30) NULL,
    puesto VARCHAR(50) NULL,
    instagram VARCHAR(100) NULL,
    imagen_url VARCHAR(255) NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_avisos_emprendedor (id_emprendedor),
    KEY idx_avisos_feria (id_feria),
    CONSTRAINT fk_avisos_emprendedor FOREIGN KEY (id_emprendedor)
        REFERENCES Usuarios (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_avisos_feria FOREIGN KEY (id_feria)
        REFERENCES Ferias (id_feria) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. Permisos por rol
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS permisos (
    id_rol INT NOT NULL,
    permiso VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_rol, permiso),
    CONSTRAINT fk_permisos_rol FOREIGN KEY (id_rol)
        REFERENCES roles (id_rol) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO permisos (id_rol, permiso) VALUES
    (1, 'ver_ferias'),
    (1, 'publicar_resenas'),
    (1, 'guardar_favoritos'),
    (2, 'publicar_avisos'),
    (3, 'registrar_ferias'),
    (3, 'eliminar_ferias')
ON DUPLICATE KEY UPDATE permiso = VALUES(permiso);

-- ============================================================
-- MIGRACIÓN PARA BASES DE DATOS YA CREADAS
-- Agrega columnas nuevas sin borrar información.
-- ============================================================
DROP PROCEDURE IF EXISTS feriando_add_column;

DELIMITER $$
CREATE PROCEDURE feriando_add_column(
    IN p_tabla VARCHAR(64),
    IN p_columna VARCHAR(64),
    IN p_definicion TEXT
)
BEGIN
    IF (
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_tabla
          AND COLUMN_NAME = p_columna
    ) = 0 THEN
        SET @feriando_sql = CONCAT(
            'ALTER TABLE `', p_tabla, '` ADD COLUMN `', p_columna, '` ', p_definicion
        );
        PREPARE feriando_stmt FROM @feriando_sql;
        EXECUTE feriando_stmt;
        DEALLOCATE PREPARE feriando_stmt;
    END IF;
END$$
DELIMITER ;

CALL feriando_add_column('Usuarios', 'telefono', 'VARCHAR(30) NULL');
CALL feriando_add_column('Usuarios', 'fecha_registro', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

CALL feriando_add_column('Perfiles_Emprendedor', 'instagram', 'VARCHAR(100) NULL');
CALL feriando_add_column('Perfiles_Emprendedor', 'foto_url', 'VARCHAR(255) NULL');
CALL feriando_add_column('Perfiles_Emprendedor', 'telefono', 'VARCHAR(30) NULL');
CALL feriando_add_column('Perfiles_Emprendedor', 'fecha_creacion', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

CALL feriando_add_column('Ferias', 'imagen_url', 'VARCHAR(255) NULL');
CALL feriando_add_column('Ferias', 'instagram', 'VARCHAR(100) NULL');
CALL feriando_add_column('Ferias', 'fecha_creacion', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

CALL feriando_add_column('Feria_Puestos', 'lugar', 'VARCHAR(150) NULL');
CALL feriando_add_column('Feria_Puestos', 'hora', 'VARCHAR(30) NULL');

CALL feriando_add_column('Avisos', 'lugar', 'VARCHAR(150) NULL');
CALL feriando_add_column('Avisos', 'hora', 'VARCHAR(30) NULL');
CALL feriando_add_column('Avisos', 'puesto', 'VARCHAR(50) NULL');
CALL feriando_add_column('Avisos', 'instagram', 'VARCHAR(100) NULL');
CALL feriando_add_column('Avisos', 'imagen_url', 'VARCHAR(255) NULL');

CALL feriando_add_column('resenas', 'id_emprendedor', 'INT NULL');

DROP PROCEDURE IF EXISTS feriando_add_column;

-- ============================================================
-- MIGRACIÓN DE ÍNDICES Y RELACIONES
-- Las bases creadas antes de esta versión no tenían todas las
-- claves foráneas ni la clave única de favoritos. Estas rutinas
-- las agregan sin borrar datos y se pueden ejecutar varias veces.
-- ============================================================

-- 1. Limpiar registros huérfanos para poder crear las relaciones.
UPDATE Avisos a
LEFT JOIN Ferias f ON a.id_feria = f.id_feria
SET a.id_feria = NULL
WHERE a.id_feria IS NOT NULL AND f.id_feria IS NULL;

DELETE a FROM Avisos a
LEFT JOIN Usuarios u ON a.id_emprendedor = u.id_usuario
WHERE u.id_usuario IS NULL;

DELETE p FROM Perfiles_Emprendedor p
LEFT JOIN Usuarios u ON p.id_usuario = u.id_usuario
WHERE u.id_usuario IS NULL;

DELETE fp FROM Feria_Puestos fp
LEFT JOIN Ferias f ON fp.id_feria = f.id_feria
LEFT JOIN Perfiles_Emprendedor p ON fp.id_emprendedor = p.id_emprendedor
WHERE f.id_feria IS NULL OR p.id_emprendedor IS NULL;

DELETE fav FROM Favoritos fav
LEFT JOIN Ferias f ON fav.id_feria = f.id_feria
LEFT JOIN Usuarios u ON fav.id_consumidor = u.id_usuario
WHERE f.id_feria IS NULL OR u.id_usuario IS NULL;

DELETE r FROM resenas r
LEFT JOIN Ferias f ON r.id_feria = f.id_feria
LEFT JOIN Usuarios u ON r.id_consumidor = u.id_usuario
WHERE (r.id_feria IS NOT NULL AND f.id_feria IS NULL) OR u.id_usuario IS NULL;

-- Quitar favoritos duplicados antes de crear la clave única.
DELETE f1 FROM Favoritos f1
INNER JOIN Favoritos f2
    ON f1.id_consumidor = f2.id_consumidor
   AND f1.id_feria = f2.id_feria
   AND f1.id_favorito > f2.id_favorito;

-- 2. Procedimientos auxiliares para índices y claves foráneas.
DROP PROCEDURE IF EXISTS feriando_add_index;
DROP PROCEDURE IF EXISTS feriando_add_fk;

DELIMITER $$
CREATE PROCEDURE feriando_add_index(
    IN p_tabla VARCHAR(64),
    IN p_indice VARCHAR(64),
    IN p_columnas TEXT,
    IN p_unico TINYINT
)
BEGIN
    -- No agregar el índice si ya existe otro con las mismas columnas.
    SELECT COUNT(*) INTO @feriando_existe
    FROM (
        SELECT INDEX_NAME,
               REPLACE(GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX), ' ', '') AS columnas,
               MAX(NON_UNIQUE) AS no_unico
        FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_tabla
        GROUP BY INDEX_NAME
    ) AS indices
    WHERE indices.columnas = REPLACE(p_columnas, ' ', '')
      AND (p_unico = 0 OR indices.no_unico = 0);

    IF @feriando_existe = 0 THEN
        SET @feriando_sql = CONCAT(
            'ALTER TABLE `', p_tabla, '` ADD ',
            IF(p_unico = 1, 'UNIQUE ', ''),
            'INDEX `', p_indice, '` (', p_columnas, ')'
        );
        PREPARE feriando_stmt FROM @feriando_sql;
        EXECUTE feriando_stmt;
        DEALLOCATE PREPARE feriando_stmt;
    END IF;
END$$

CREATE PROCEDURE feriando_add_fk(
    IN p_tabla VARCHAR(64),
    IN p_fk VARCHAR(64),
    IN p_columnas TEXT,
    IN p_ref_tabla VARCHAR(64),
    IN p_ref_columnas TEXT,
    IN p_on_delete VARCHAR(20)
)
BEGIN
    -- No agregar la relación si esa columna ya tiene una clave foránea
    -- hacia la misma tabla, aunque tenga otro nombre.
    SELECT COUNT(*) INTO @feriando_existe
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = p_tabla
      AND COLUMN_NAME = p_columnas
      AND REFERENCED_TABLE_NAME = p_ref_tabla
      AND REFERENCED_COLUMN_NAME = p_ref_columnas;

    IF @feriando_existe = 0 THEN
        SET @feriando_sql = CONCAT(
            'ALTER TABLE `', p_tabla, '` ADD CONSTRAINT `', p_fk,
            '` FOREIGN KEY (', p_columnas, ') REFERENCES `', p_ref_tabla,
            '` (', p_ref_columnas, ') ON DELETE ', p_on_delete,
            ' ON UPDATE CASCADE'
        );
        PREPARE feriando_stmt FROM @feriando_sql;
        EXECUTE feriando_stmt;
        DEALLOCATE PREPARE feriando_stmt;
    END IF;
END$$
DELIMITER ;

-- 3. Índices que faltaban en instalaciones antiguas.
CALL feriando_add_index('Usuarios', 'uq_usuarios_email', 'email', 1);
CALL feriando_add_index('Usuarios', 'idx_usuarios_rol', 'id_rol', 0);
CALL feriando_add_index('Perfiles_Emprendedor', 'uq_perfil_usuario', 'id_usuario', 1);
CALL feriando_add_index('Ferias', 'idx_ferias_organizador', 'id_organizador', 0);
CALL feriando_add_index('Ferias', 'idx_ferias_estado', 'estado', 0);
CALL feriando_add_index('Fechas_Feria', 'idx_fechas_feria', 'id_feria', 0);
CALL feriando_add_index('Fechas_Feria', 'idx_fechas_inicio', 'fecha_inicio', 0);
CALL feriando_add_index('Feria_Puestos', 'idx_puestos_feria', 'id_feria', 0);
CALL feriando_add_index('Feria_Puestos', 'idx_puestos_emprendedor', 'id_emprendedor', 0);
CALL feriando_add_index('Favoritos', 'uq_favorito', 'id_consumidor, id_feria', 1);
CALL feriando_add_index('Favoritos', 'idx_favoritos_feria', 'id_feria', 0);
CALL feriando_add_index('resenas', 'idx_resenas_consumidor', 'id_consumidor', 0);
CALL feriando_add_index('resenas', 'idx_resenas_feria', 'id_feria', 0);
CALL feriando_add_index('resenas', 'idx_resenas_emprendedor', 'id_emprendedor', 0);
CALL feriando_add_index('Avisos', 'idx_avisos_emprendedor', 'id_emprendedor', 0);
CALL feriando_add_index('Avisos', 'idx_avisos_feria', 'id_feria', 0);

-- 4. Claves foráneas que faltaban en instalaciones antiguas.
CALL feriando_add_fk('Perfiles_Emprendedor', 'fk_perfil_usuario', 'id_usuario', 'Usuarios', 'id_usuario', 'CASCADE');
CALL feriando_add_fk('Ferias', 'fk_ferias_organizador', 'id_organizador', 'Usuarios', 'id_usuario', 'CASCADE');
CALL feriando_add_fk('Fechas_Feria', 'fk_fechas_feria', 'id_feria', 'Ferias', 'id_feria', 'CASCADE');
CALL feriando_add_fk('Feria_Puestos', 'fk_puestos_feria', 'id_feria', 'Ferias', 'id_feria', 'CASCADE');
CALL feriando_add_fk('Feria_Puestos', 'fk_puestos_emprendedor', 'id_emprendedor', 'Perfiles_Emprendedor', 'id_emprendedor', 'CASCADE');
CALL feriando_add_fk('Favoritos', 'fk_favoritos_consumidor', 'id_consumidor', 'Usuarios', 'id_usuario', 'CASCADE');
CALL feriando_add_fk('Favoritos', 'fk_favoritos_feria', 'id_feria', 'Ferias', 'id_feria', 'CASCADE');
CALL feriando_add_fk('resenas', 'fk_resenas_consumidor', 'id_consumidor', 'Usuarios', 'id_usuario', 'CASCADE');
CALL feriando_add_fk('resenas', 'fk_resenas_feria', 'id_feria', 'Ferias', 'id_feria', 'CASCADE');
CALL feriando_add_fk('resenas', 'fk_resenas_emprendedor', 'id_emprendedor', 'Perfiles_Emprendedor', 'id_emprendedor', 'SET NULL');
CALL feriando_add_fk('Avisos', 'fk_avisos_emprendedor', 'id_emprendedor', 'Usuarios', 'id_usuario', 'CASCADE');
CALL feriando_add_fk('Avisos', 'fk_avisos_feria', 'id_feria', 'Ferias', 'id_feria', 'SET NULL');

DROP PROCEDURE IF EXISTS feriando_add_index;
DROP PROCEDURE IF EXISTS feriando_add_fk;

-- ============================================================
-- FIN
-- ============================================================
