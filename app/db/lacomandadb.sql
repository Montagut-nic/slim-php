-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 18:14:10
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomandadb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `ID_empleado` int(11) NOT NULL,
  `ID_tipo_empleado` int(11) NOT NULL,
  `nombre_empleado` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(1) NOT NULL,
  `cantidad_operaciones` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`ID_empleado`, `ID_tipo_empleado`, `nombre_empleado`, `usuario`, `clave`, `fecha_registro`, `estado`,`cantidad_operaciones`) VALUES
(1, 5, 'Mateo', 'socio2', 'socio2', '2023-06-06 00:00:00', 'A',13),
(4, 1, 'Mario', 'bartender', 'bartender', '2023-06-06 00:00:00', 'A',25),
(12, 5, 'Martin', 'socio', 'socio', '2023-06-07 00:00:00', 'A',14),
(62, 5, 'Nico', 'admin', 'admin', '2023-06-09 00:00:00', 'A',10),
(72, 4, 'Camila', 'mozo', 'mozo', '2023-06-04 00:00:00', 'A',9),
(82, 2, 'Marcos', 'cervecero', 'cervecero', '2023-06-07 00:00:00', 'A',11),
(92, 3, 'Lucas', 'cocinero', 'cocinero', '2023-06-08 00:00:00', 'A',12),
(102, 4, 'Juan', 'mozo2', 'mozo2', '2023-06-09 22:00:00', 'B',9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedidos`
--

CREATE TABLE `estado_pedidos` (
  `id_estado_pedidos` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estado_pedidos`
--

INSERT INTO `estado_pedidos` (`id_estado_pedidos`, `descripcion`) VALUES
(1, 'Pendiente'),
(2, 'En preparacion'),
(3, 'Listo para servir'),
(4, 'Entregado'),
(5, 'Cancelado'),
(6, 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre`, `precio`, `id_sector`) VALUES
(2, 'Pizza Muzza', 178, 3),
(12, 'Mila Napo', 90, 3),
(22, 'Cerveza', 60, 2),
(42, 'Empanada', 30, 3),
(52, 'Vino', 100, 1),
(62, 'Jugo', 60, 1),
(72, 'Canelones', 120, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `codigo_mesa` varchar(5) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `foto` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`codigo_mesa`, `estado`, `foto`) VALUES
('MES01', 'Con cliente esperando pedido', './Fotos/Mesas/MES01.jpg'),
('MES02', 'Cerrada', NULL),
('MES03', 'Cerrada', './Fotos/Mesas/MES03.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `codigo` varchar(5) NOT NULL,
  `id_estado_pedidos` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_entrega_estimada` time DEFAULT NULL,
  `hora_entrega_real` time DEFAULT NULL,
  `id_mesa` varchar(5) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`codigo`, `id_estado_pedidos`, `fecha`, `hora_inicial`, `hora_entrega_estimada`, `hora_entrega_real`, `id_mesa`, `id_menu`, `id_mozo`, `id_encargado`, `nombre_cliente`) VALUES
('1crva', 6, '2023-06-24', '18:03:00', '18:50:00', '18:30:00', 'MES02', 52, 72, 4, 'Moni'),
('7qy38', 4, '2023-06-24', '19:36:00', '20:07:00', '19:58:00', 'MES03', 12, 72, 92, 'Pepe'),
('hnr2t', 5, '2023-06-24', '23:03:00', NULL, NULL, 'MES03', 12, 72, NULL, 'Pepe'),
('iuypn', 5, '2023-06-25', '17:22:00', NULL, NULL, 'MES01', 2, 72, NULL, NULL),
('j2j0d', 6, '2023-06-24', '19:42:00', NULL, NULL, 'MES02', 72, 72, NULL, 'Moni'),
('mzp34', 6, '2023-06-23', '17:10:00', '17:14:00', '17:17:00', 'MES02', 62, 72, 4, NULL),
('ortcx', 6, '2023-06-24', '18:36:00', '18:41:00', '18:31:00', 'MES02', 52, 72, 4, 'Moni'),
('p3x50', 6, '2023-06-24', '19:42:00', NULL, NULL, 'MES02', 72, 72, NULL, 'Moni'),
('q1xtm', 6, '2023-06-24', '19:42:00', NULL, NULL, 'MES02', 72, 72, NULL, 'Moni'),
('qdgz8', 6, '2023-06-23', '12:15:00', NULL, NULL, 'MES02', 52, 72, NULL, NULL),
('qx2n8', 6, '2023-06-24', '19:42:00', NULL, NULL, 'MES02', 72, 72, NULL, 'Moni'),
('spk90', 1, '2023-06-26', '19:53:00', NULL, NULL, 'MES01', 2, 72, NULL, 'Coqui'),
('u8o4e', 6, '2023-06-24', '18:36:00', NULL, NULL, 'MES02', 52, 72, NULL, 'Moni'),
('ua3y8', 6, '2023-06-25', '17:10:00', NULL, NULL, 'MES01', 72, 72, NULL, NULL),
('uxkr5', 6, '2023-06-24', '18:34:00', NULL, NULL, 'MES02', 52, 72, NULL, 'Moni'),
('v3iye', 6, '2023-06-24', '19:42:00', NULL, NULL, 'MES02', 72, 72, NULL, 'Moni'),
('vnzci', 6, '2023-06-24', '18:33:00', NULL, NULL, 'MES02', 52, 72, NULL, 'Moni'),
('zixal', 6, '2023-06-25', '17:10:00', NULL, NULL, 'MES01', 22, 72, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoempleado`
--

CREATE TABLE `tipoempleado` (
  `ID_tipo_empleado` int(11) NOT NULL,
  `Descripcion` varchar(30) NOT NULL,
  `Estado` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipoempleado`
--

INSERT INTO `tipoempleado` (`ID_tipo_empleado`, `Descripcion`, `Estado`) VALUES
(1, 'Bartender', 'A'),
(2, 'Cervecero', 'A'),
(3, 'Cocinero', 'A'),
(4, 'Mozo', 'A'),
(5, 'Socio', 'A');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`ID_empleado`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD PRIMARY KEY (`id_estado_pedidos`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`codigo_mesa`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `tipoempleado`
--
ALTER TABLE `tipoempleado`
  ADD PRIMARY KEY (`ID_tipo_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `ID_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `tipoempleado`
--
ALTER TABLE `tipoempleado`
  MODIFY `ID_tipo_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
