-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-09-2026 a las 21:18:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `feriando`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `id_consumidor` int(11) NOT NULL,
  `id_feria` int(11) NOT NULL,
  `fecha_guardado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fechas_feria`
--

CREATE TABLE `fechas_feria` (
  `id_fecha` int(11) NOT NULL,
  `id_feria` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ferias`
--

CREATE TABLE `ferias` (
  `id_feria` int(11) NOT NULL,
  `id_organizador` int(11) NOT NULL,
  `nombre_feria` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion_texto` varchar(255) NOT NULL,
  `latitud` decimal(9,6) DEFAULT NULL,
  `longitud` decimal(9,6) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'activa',
  `imagen_url` varchar(255) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feria_puestos`
--

CREATE TABLE `feria_puestos` (
  `id_puesto` int(11) NOT NULL,
  `id_feria` int(11) NOT NULL,
  `id_emprendedor` int(11) NOT NULL,
  `numero_puesto` varchar(20) DEFAULT NULL,
  `estado_solicitud` varchar(20) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles_emprendedor`
--

CREATE TABLE `perfiles_emprendedor` (
  `id_emprendedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_marca` varchar(100) NOT NULL,
  `categoria_producto` varchar(50) DEFAULT NULL,
  `descripcion_emprendimiento` text DEFAULT NULL,
  `redes_sociales` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(11) NOT NULL,
  `id_consumidor` int(11) NOT NULL,
  `id_feria` int(11) DEFAULT NULL,
  `id_emprendedor` int(11) DEFAULT NULL,
  `calificacion` int(11) NOT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_consumidor` (`id_consumidor`),
  ADD KEY `id_feria` (`id_feria`);

--
-- Indices de la tabla `fechas_feria`
--
ALTER TABLE `fechas_feria`
  ADD PRIMARY KEY (`id_fecha`),
  ADD KEY `id_feria` (`id_feria`);

--
-- Indices de la tabla `ferias`
--
ALTER TABLE `ferias`
  ADD PRIMARY KEY (`id_feria`),
  ADD KEY `id_organizador` (`id_organizador`);

--
-- Indices de la tabla `feria_puestos`
--
ALTER TABLE `feria_puestos`
  ADD PRIMARY KEY (`id_puesto`),
  ADD KEY `id_feria` (`id_feria`),
  ADD KEY `id_emprendedor` (`id_emprendedor`);

--
-- Indices de la tabla `perfiles_emprendedor`
--
ALTER TABLE `perfiles_emprendedor`
  ADD PRIMARY KEY (`id_emprendedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_consumidor` (`id_consumidor`),
  ADD KEY `id_feria` (`id_feria`),
  ADD KEY `id_emprendedor` (`id_emprendedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fechas_feria`
--
ALTER TABLE `fechas_feria`
  MODIFY `id_fecha` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ferias`
--
ALTER TABLE `ferias`
  MODIFY `id_feria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `feria_puestos`
--
ALTER TABLE `feria_puestos`
  MODIFY `id_puesto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfiles_emprendedor`
--
ALTER TABLE `perfiles_emprendedor`
  MODIFY `id_emprendedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_consumidor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_feria`) REFERENCES `ferias` (`id_feria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `fechas_feria`
--
ALTER TABLE `fechas_feria`
  ADD CONSTRAINT `fechas_feria_ibfk_1` FOREIGN KEY (`id_feria`) REFERENCES `ferias` (`id_feria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ferias`
--
ALTER TABLE `ferias`
  ADD CONSTRAINT `ferias_ibfk_1` FOREIGN KEY (`id_organizador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `feria_puestos`
--
ALTER TABLE `feria_puestos`
  ADD CONSTRAINT `feria_puestos_ibfk_1` FOREIGN KEY (`id_feria`) REFERENCES `ferias` (`id_feria`) ON DELETE CASCADE,
  ADD CONSTRAINT `feria_puestos_ibfk_2` FOREIGN KEY (`id_emprendedor`) REFERENCES `perfiles_emprendedor` (`id_emprendedor`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perfiles_emprendedor`
--
ALTER TABLE `perfiles_emprendedor`
  ADD CONSTRAINT `perfiles_emprendedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_consumidor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_feria`) REFERENCES `ferias` (`id_feria`) ON DELETE SET NULL,
  ADD CONSTRAINT `resenas_ibfk_3` FOREIGN KEY (`id_emprendedor`) REFERENCES `perfiles_emprendedor` (`id_emprendedor`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
