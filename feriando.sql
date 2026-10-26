-- ============================================================
-- BASE DE DATOS: LOCALIZACIÓN DE FERIAS
-- ============================================================
CREATE DATABASE IF NOT EXISTS feriando;
USE feriando;

-- 1. Tabla: Usuarios
CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL, -- 'consumidor', 'emprendedor', 'organizador'
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla: Perfiles_Emprendedor
CREATE TABLE IF NOT EXISTS Perfiles_Emprendedor (
    id_emprendedor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre_marca VARCHAR(100) NOT NULL,
    categoria_producto VARCHAR(50),
    descripcion_emprendimiento TEXT,
    redes_sociales TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

-- 3. Tabla: Ferias
CREATE TABLE IF NOT EXISTS Ferias (
    id_feria INT AUTO_INCREMENT PRIMARY KEY,
    id_organizador INT NOT NULL,
    nombre_feria VARCHAR(120) NOT NULL,
    descripcion TEXT,
    direccion_texto VARCHAR(255) NOT NULL,
    latitud DECIMAL(9,6),
    longitud DECIMAL(9,6),
    categoria VARCHAR(50),
    estado VARCHAR(20) DEFAULT 'activa', -- 'activa', 'cancelada', 'finalizada'
    imagen_url VARCHAR(255),             -- Para el flyer/portada de la feria
    instagram VARCHAR(100),              -- Red social de la feria
    FOREIGN KEY (id_organizador) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

-- 4. Tabla: Fechas_Feria
CREATE TABLE IF NOT EXISTS Fechas_Feria (
    id_fecha INT AUTO_INCREMENT PRIMARY KEY,
    id_feria INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    FOREIGN KEY (id_feria) REFERENCES Ferias(id_feria) ON DELETE CASCADE
);

-- 5. Tabla: Feria_Puestos
CREATE TABLE IF NOT EXISTS Feria_Puestos (
    id_puesto INT AUTO_INCREMENT PRIMARY KEY,
    id_feria INT NOT NULL,
    id_emprendedor INT NOT NULL,
    numero_puesto VARCHAR(20),
    estado_solicitud VARCHAR(20) DEFAULT 'pendiente', -- 'pendiente', 'aceptado', 'rechazado'
    FOREIGN KEY (id_feria) REFERENCES Ferias(id_feria) ON DELETE CASCADE,
    FOREIGN KEY (id_emprendedor) REFERENCES Perfiles_Emprendedor(id_emprendedor) ON DELETE CASCADE
);

-- 6. Tabla: Favoritos
CREATE TABLE IF NOT EXISTS Favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_consumidor INT NOT NULL,
    id_feria INT NOT NULL,
    fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_consumidor) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_feria) REFERENCES Ferias(id_feria) ON DELETE CASCADE
);

-- 7. Tabla: Resenas
CREATE TABLE IF NOT EXISTS Resenas (
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_consumidor INT NOT NULL,
    id_feria INT NULL,
    id_emprendedor INT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_resena TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_consumidor) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_feria) REFERENCES Ferias(id_feria) ON DELETE SET NULL,
    FOREIGN KEY (id_emprendedor) REFERENCES Perfiles_Emprendedor(id_emprendedor) ON DELETE SET NULL
);
