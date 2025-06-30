-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2025 a las 13:48:20
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `ID` int(11) NOT NULL,
  `Catergoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`ID`, `Catergoria`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('oficina','comida') NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `tipo`, `activo`) VALUES
(1, 'Material de Oficina', 'Productos para uso en oficina', 'oficina', 1),
(2, 'Alimentos', 'Productos alimenticios', 'comida', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion`
--

CREATE TABLE `informacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `fecha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informacion`
--

INSERT INTO `informacion` (`id`, `nombre`, `usuario`, `clave`, `email`, `cedula`, `id_cargo`, `fecha`) VALUES
(1, 'Eduar Suarez', 'EduarSG', '31466704', 'eduar@gmail.com', '31466704', 1, '12/05/2025'),
(2, 'Jose Graterol', 'JoseSG', '31466704', 'jose@gmail.com', '31466705', 2, '12/05/2025'),
(3, 'Eduar Graterol', 'EduarGG', '31466704', 'eduargraterol@gmail.com', '31466706', 1, '15/05/2025'),
(4, 'edua', 'eduarjj', '31466704', 'jos@gmai.com', '32466714', 2, '15/05/2025'),
(5, 'Greymar Medina', 'ggss09', '123456789', 'ggss2004m@gmail.com', '30916457', 1, '22/05/2025'),
(6, 'Edwin Buzz Lightyear Aldrin', 'Lightyear', 'wawawa', 'buzz.lightyear@gmail.com', '30000000', 2, '29/05/2025'),
(7, 'Sarai Rufina', 'Sarai999', 'ayyonose', 'sarai@gmail.com', '32029564', 1, '02/06/2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cantegoria_id` int(11) NOT NULL,
  `unidad_medida` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `cantegoria_id`, `unidad_medida`, `stock`, `fecha_registro`) VALUES
(5, 'Hojas', 'Hojas de colores', 1, '50 Cajas', 4, '2025-05-28 13:43:54'),
(7, 'Naranjas', 'Naranjas Azules.', 2, '40 Kilos', 2, '2025-05-28 14:36:25'),
(8, 'Piña', 'Piña Congelada ', 2, '50 Kilos', 9, '2025-05-28 15:51:24'),
(9, 'Pan', 'Pan de Queso.', 2, '50 unidades', 12, '2025-05-28 16:36:40'),
(12, 'Lapiceros', 'Lapiceros tipo Mongol color negro', 1, '12 unidades', 2, '2025-05-29 21:49:04'),
(13, 'Soy una Locaaa', '', 2, 'la verga mia', 69, '2025-05-29 22:09:41'),
(14, 'Sasa', 'Webo', 2, '1 gr', 100, '2025-06-06 13:05:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_registro`
--

CREATE TABLE `solicitud_registro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `fecha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_registro`
--

INSERT INTO `solicitud_registro` (`id`, `nombre`, `usuario`, `clave`, `email`, `cedula`, `id_cargo`, `fecha`) VALUES
(15, 'HERACLES ENMANUEL SANCHEZ COELLO', 'HERCULITO', '123', 'hhhhh@GMAIL.COM', '31987430', 2, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `informacion`
--
ALTER TABLE `informacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`id_cargo`),
  ADD KEY `tipo_rol` (`id_cargo`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movimientos_producto` (`producto_id`),
  ADD KEY `fk_movimientos_usuario` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitud_registro`
--
ALTER TABLE `solicitud_registro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`id_cargo`),
  ADD KEY `tipo_rol` (`id_cargo`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `informacion`
--
ALTER TABLE `informacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `solicitud_registro`
--
ALTER TABLE `solicitud_registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `fk_movimientos_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_movimientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `informacion` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
