-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 09, 2024 at 09:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Kisma`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `nombre_administrador` varchar(255) NOT NULL,
  `usuario_administrador` varchar(100) NOT NULL,
  `correo_administrador` varchar(255) NOT NULL,
  `contrasena_administrador` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id_administrador`, `nombre_administrador`, `usuario_administrador`, `correo_administrador`, `contrasena_administrador`, `fecha_registro`, `activo`) VALUES
(1, 'Administrador2', 'Administrador2', 'Administrador2@gmail.com', '$2y$10$fkMFB3gLPZURUbUmFPR3Y.60v8Ji5YYddTVviuT4.kKqVQQf7fPpC', '2024-11-25 01:30:57', 1),
(4, 'Administrador1', 'Administrador1', 'Administrador1@gmail.com', '$2y$10$F2kAhXjL6W/2OEBtb.mGguZTDdzJOtfPSCEzguWJI7W.jj3BV32j2', '2024-11-25 01:31:39', 1),
(6, 'Administrador3', 'Administrador3', 'Administrador3@gmail.com', '$2y$10$UpceBIViAwPfrskAAt9hQOdUsp62RkAORbeNK8lZPuKFMmqjAmBni', '2024-11-25 01:33:18', 1),
(7, 'administradorprueba', 'administradorprueba', 'administradorprueba@gmail.com', '$2y$10$1dVEXP3TjHodKakN7iH8Re0DMSBnwgGJsPbrEtCBpf3dd55GGB2h6', '2024-11-27 01:12:10', 1),
(8, 'newadmin', 'newadmin', 'newadmin@gmial.com', '$2y$10$VsjcAUYZ7M7CIe.tAOdrM.u4HORWT1nrCQxZuQEFntN3OGuEuSNh6', '2024-12-07 21:37:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `calificacion_restaurante` tinyint(1) DEFAULT NULL CHECK (`calificacion_restaurante` between 1 and 5),
  `calificacion_empleado` tinyint(1) DEFAULT NULL CHECK (`calificacion_empleado` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_calificacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre_completo`, `nombre_usuario`, `correo`, `contrasena`, `telefono`, `created_at`) VALUES
(1, 'Christian Alberth Ramos Manzanares', 'kisma.256', 'ramoskeepler@gmail.com', '$2y$10$UfQ33IiVwLzyL2YoJ2m3sOB6qeDU6HfgkmATWj/UiF9fJDkpHcq12', '8341450993', '2024-11-17 15:26:00'),
(2, 'cliente1', 'cliente1', 'cliente1@gmail.com', '$2y$10$H0KxLH/3LYNSO4TfkH0mgeytgyMTCkCcSwzB1UKdfWh1I6UDH43O6', '83412345678', '2024-11-17 20:12:48'),
(3, 'cliente2', 'cliente2', 'cliente2@gmail.com', '$2y$10$CTssGpWF1OVWSnkeow85sewqV9E1fwQqCtUvnxpAcRfk/icaBb1nG', '83412345678', '2024-11-17 21:38:07'),
(4, 'cliente4', 'cliente4', 'cliente4@gmail.com', '$2y$10$YAolmxCCLFVAP/xIaAI9p.vwc8x.ZVIbRUSHj3.P6u6HgaGoek8pG', '81233409233', '2024-11-24 01:25:26'),
(8, 'Cliente5', 'Cliente5', 'Cliente5@gmail.com', '$2y$10$PO81s6MeueH5ZHMPqaHSv.hZP3lW4ix4ZeoMCy5E/RhUUPCcmhgc6', '12334457234', '2024-11-24 01:28:08'),
(9, 'cliente3', 'cliente3', 'cliente3@gmail.com', '$2y$10$evZrAsCgBvXfV/dwDesUYe38CrG84jJAf14xFk3fCKCtyq9en4ya.', '8123987423', '2024-11-24 01:56:16'),
(11, 'cliente7', 'cliente7', 'cliente7@gmail.com', '$2y$10$fPtlHf26ALuS0R0ppeaGdOPfjuQVViomydmEOcDGWJGgTwmourkmS', '8341238452', '2024-11-24 16:26:48'),
(12, 'clienteprueba', 'clienteprueba', 'clienteprueba@prueba.com', '$2y$10$bqpi0HPCVzGMwjioX9G1muojCAX8v5ZCJDNi2j6KAgBlLxTxCabAe', '8341450993', '2024-11-27 00:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 2, 1, 3, 59.00),
(2, 2, 1, 1, 29.00),
(3, 4, 3, 1, 69.00),
(4, 6, 3, 1, 69.00),
(5, 8, 3, 1, 69.00),
(6, 9, 5, 2, 59.00),
(7, 10, 6, 1, 159.00),
(9, 12, 2, 2, 500.00),
(10, 13, 2, 2, 2.00),
(11, 14, 3, 3, 3.00),
(12, 15, 4, 4, 4.00),
(13, 20, 13, 5, 99.00),
(14, 21, 13, 5, 99.00),
(15, 22, 13, 5, 99.00),
(16, 23, 1, 2, 100.50),
(17, 24, 4, 2, 59.00),
(18, 25, 1, 1, 100.50),
(19, 25, 4, 1, 59.00),
(20, 25, 10, 1, 89.00),
(21, 25, 13, 1, 99.00),
(22, 25, 13, 1, 99.00);

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `usuario_empleado` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono_empleado` varchar(15) NOT NULL,
  `correo_empleado` varchar(100) NOT NULL,
  `contrasena_empleado` varchar(255) NOT NULL,
  `tipo_vehiculo` enum('Motocicleta','Automóvil','Camioneta','Cuatrimoto') NOT NULL,
  `anio_vehiculo` year(4) NOT NULL CHECK (`anio_vehiculo` >= 2018),
  `numero_placa` varchar(20) NOT NULL,
  `numero_cuenta` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `nombre_completo`, `usuario_empleado`, `fecha_nacimiento`, `telefono_empleado`, `correo_empleado`, `contrasena_empleado`, `tipo_vehiculo`, `anio_vehiculo`, `numero_placa`, `numero_cuenta`, `fecha_registro`) VALUES
(1, 'empleado1', 'empleado1', '2000-11-11', '8349321232', 'empleado1@gmail.com', '$2y$10$Dafr7pYnBLFW0ZuljrYZ9OYv9vxitIq/m2k5fhsogwb6qA5wyvR0i', 'Motocicleta', '2021', 'XSQ-S2-322-43', '00000023100', '2024-11-18 05:01:05'),
(8, 'empleado2', 'empleado2', '2002-02-22', '0912739871', 'empleado2@gmail.com', '$2y$10$CbfJwO8VGqw11VLOt5Iw4uSSBin.PcN0Btt.rixY8iXvzCm5aa77u', 'Cuatrimoto', '2025', 'ASD-324-GD4-SS', '71298371298364', '2024-11-24 16:43:21'),
(9, 'empleado3', 'empleado3', '2002-02-22', '1289371287', 'empleado3@gmail.com', '$2y$10$M/MSsMYTef.ZosDrCed57uG9TepeRdxoM6VbKPhkpvlJqk.Faf.0y', 'Motocicleta', '2024', '23-VDSE-4-EW2', '123127154623', '2024-11-24 16:45:13'),
(10, 'empleado4', 'empleado4', '2002-02-22', '1289371287', 'empleado4@gmail.com', '$2y$10$CJcARtELEsSHb95TMTXj2uBKRKY8KaY4dbabISSA47x3tpKCxXZdW', 'Automóvil', '2023', 'CE-VDSE-4-GFD', '871265742312', '2024-11-24 16:52:28'),
(11, 'Empleado5', 'Empleado5Empleado5', '2003-12-22', '123123123123', 'Empleado5@gmail.com', '$2y$10$GSHAYAuL/y2Vkx375Ad3CuMGjHSgIXUa8TA8Cc6KOHwv3Zp4Pc2zG', 'Camioneta', '2020', '7GS-S2-23D-42', '3465475685634', '2024-11-26 17:26:33'),
(26, 'empleado6', 'empleado6', '2000-03-31', '273198236', 'empleado6@gmail.com', '$2y$10$93xzmAQDTBl8Ua.nx5Sf4.FCpye4k2bu0flfc7wN1WL1BvgNfSoTG', 'Motocicleta', '2020', '21093712078', '1207328096287', '2024-11-26 17:31:11'),
(29, 'empleado8', 'empleado8', '2000-03-31', '273198236', 'empleado8@gmail.com', '$2y$10$xIAAQtvqPL8KYaGu9e4ggOtu1boDFdy/RtSFEk7C9eM95aBw5OMJa', 'Motocicleta', '2020', '21093712071231', '12073280962871231', '2024-11-26 17:38:12'),
(35, 'empleado9', 'empleado9', '2000-03-31', '2731982362', 'empleado9@gmail.com', '$2y$10$F12xoZHaKqErmQhKJcuIZuarvGccEcmAHekxub59ZrTjcHug7KjLK', 'Motocicleta', '2020', '210937120', '2120732809', '2024-11-26 17:43:51'),
(36, 'empleadoprueba', 'empleadoprueba', '2002-04-04', '8451510678', 'empleadoprueba@gmail.com', '$2y$10$65sxl0f1hsRVPMrI32iLFOHaCdNzlKbIuutHC0cLCOMLsi4zxVDxi', 'Motocicleta', '2020', 'SDF-554-DFSD-2', '12033324566287', '2024-11-27 00:59:54'),
(37, 'newempleado', 'newempleado', '2000-03-22', '8981273871239', 'newempleado@gmail.com', '$2y$10$sNzcuapd47M6Sx8dyInv4Oo7LaTt33QSxonPosci4d5N098M8MPUi', 'Motocicleta', '2019', 'FFF-S2-888-43', '12873182758734681', '2024-12-07 21:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `estados_pedido`
--

CREATE TABLE `estados_pedido` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendiente','Preparando','En Camino','Entregado') DEFAULT 'Pendiente',
  `direccion_entrega` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `id_restaurante`, `id_empleado`, `fecha_pedido`, `estado`, `direccion_entrega`, `total`) VALUES
(2, 1, 1, NULL, '2024-11-26 04:43:20', 'Entregado', '', 206.00),
(3, 1, 1, NULL, '2024-11-26 19:07:17', 'En Camino', 'Dirección no especificada', 0.00),
(4, 1, 1, NULL, '2024-11-26 19:07:17', 'En Camino', 'Dirección no especificada', 69.00),
(6, 1, 1, NULL, '2024-11-26 19:07:51', 'Entregado', 'Dirección no especificada', 69.00),
(8, 1, 1, NULL, '2024-11-26 19:13:01', 'Entregado', 'Dirección no especificada', 69.00),
(9, 3, 1, NULL, '2024-11-26 19:19:03', 'En Camino', 'Rio Del Moro', 118.00),
(10, 12, 1, NULL, '2024-11-27 00:57:04', 'Entregado', 'Rio Salado', 159.00),
(12, 2, 2, NULL, '2024-12-08 21:58:06', 'En Camino', 'Rio Salado', 1000.00),
(13, 2, 2, NULL, '2024-12-08 21:58:45', 'Entregado', '2', 4.00),
(14, 3, 3, NULL, '2024-12-08 22:06:15', 'En Camino', '3', 9.00),
(15, 4, 4, NULL, '2024-12-08 22:06:22', 'Entregado', '4', 16.00),
(20, 11, 6, NULL, '2024-12-09 00:43:38', 'Entregado', 'Universidad Politecnica de Victoria', 495.00),
(21, 11, 6, NULL, '2024-12-09 00:48:02', 'Pendiente', 'Universidad Politecnica de Victoria', 495.00),
(22, 11, 6, NULL, '2024-12-09 00:50:48', 'Pendiente', 'Universidad Politecnica de Victoria', 495.00),
(23, 1, 1, NULL, '2024-12-09 03:51:39', 'En Camino', 'Rio Del Moro', 201.00),
(24, 1, 1, NULL, '2024-12-09 03:52:44', 'Entregado', 'Calle Uva Pasa', 118.00),
(25, 1, 1, NULL, '2024-12-09 04:46:18', 'En Camino', 'Rio Del Moro', 446.50);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `id_restaurante`, `nombre_producto`, `descripcion`, `precio`, `disponible`) VALUES
(1, 1, 'Producto 1', 'Descripción del producto 1', 100.50, 1),
(2, 1, 'Producto 2', 'Descripción del producto 2', 200.00, 0),
(3, 1, 'Producto 3', 'Descripción del producto 3', 150.75, 1),
(4, 1, 'Flautas', 'Ricas', 59.00, 1),
(5, 1, 'Flautas', 'Ricas', 59.00, 1),
(6, 1, 'Pizza', 'Pizza Italiana', 159.00, 1),
(8, 1, 'Pollo Frito', 'KTM', 199.00, 1),
(9, 1, 'Quesadillas', 'Con queso', 19.00, 0),
(10, 1, 'Pescado', 'Frito', 89.00, 0),
(11, 1, 'Doritos', 'Rojos', 19.00, 0),
(12, 1, 'newproducto', 'newproducto', 299.00, 0),
(13, 1, 'Boneless', 'Pollo', 99.00, 0),
(14, 1, 'Asada', 'Roja', 19.00, 0),
(15, 1, 'Asada', 'Verde', 29.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL,
  `nombre_restaurante` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `horario` varchar(100) NOT NULL,
  `telefono_restaurante` varchar(15) NOT NULL,
  `correo_restaurante` varchar(255) NOT NULL,
  `nombre_gerente` varchar(255) NOT NULL,
  `usuario_gerente` varchar(255) NOT NULL,
  `correo_gerente` varchar(255) NOT NULL,
  `contrasena_gerente` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `nombre_restaurante`, `categoria`, `descripcion`, `direccion`, `ciudad`, `codigo_postal`, `horario`, `telefono_restaurante`, `correo_restaurante`, `nombre_gerente`, `usuario_gerente`, `correo_gerente`, `contrasena_gerente`, `fecha_registro`) VALUES
(1, 'Restaurante1', 'Helados', 'Vendemos helados', 'Rio Salado 197', 'CIUDAD VICTORIA', '87060', '7:00 AM - 21:00 PM', '8341450993', 'restaurante1@gmail.com', 'gerente1', 'gerente1', 'gerente1@gmail.com', '$2y$10$Z50no65t38Ae.y6Svqi3E.A5XJm2N39Cdj36aVxR8vO3MG2Z/52Xe', '2024-11-17 21:54:09'),
(2, 'Restaurante2', 'Helados', 'Hacemos helados', 'Restaurante2', 'Restaurante2', '981238', '9:00 - 20:20', '12983612758', 'restaurante2@gmail.com', 'gerente2', 'gerente2', 'gerente2@gmail.com', '$2y$10$n2sSPIOBVbg5VPzMw1zhHuoRQFpn0.iBrd3lhQN2cxCeH7/mLf8W.', '2024-11-24 18:11:31'),
(3, 'restaurante3', 'Pollo', 'Hacemos alitas y pollos', 'restaurante3', 'restaurante3', '0973123', '6:00 - 15:00', '1203817268', 'restaurante3@gmail.com', 'gerente3', 'gerente3', 'gerente3@gmail.com', '$2y$10$m6LzzUFgFMjE8uoFyl./7e5QG3voJXDvUreFKdkWodXZLc0hNBmfm', '2024-11-24 18:24:49'),
(4, 'Restaurante5', 'Sushi', 'Vendo Sushi', 'Sushi', 'Sushi', '87060', '9:00 AM - 20:00 PM', '83412346323', 'restaurante5@gmail.com', 'gerente5', 'gerente5', 'gerente5@gmail.com', '$2y$10$l4WJCU3T2pRNhNwKTe3HeOk2TxTVfKxlDEbcCqwmHEeYNKyaHPh6G', '2024-11-26 04:19:13'),
(5, 'restauranteprueba', 'Tacos', 'Hacemos tacos xdd porque somos unos tipos chill de cojones', 'Rio Alamo', 'CIUDAD VICTORIA', '87060', '9:00 AM - 13:00 PM', '8341450993', 'restauranteprueba@gmail.com', 'gerenteprueba', 'gerenteprueba', 'gerenteprueba@gmail.com', '$2y$10$shULYfYrqPxU29OM0j35cumtOzqE0.X/NeuCKCCk11LZdn4Vwbpqm', '2024-11-27 01:09:12'),
(6, 'newrestaurante', 'Antojitos', 'Vendemos antojitos', 'newrestaurante', 'newrestaurante', '87060', '9:00 - 22:00', '8341450993', 'newrestaurante@gmial.com', 'newgerente', 'newgerente', 'newgerente@gmail.com', '$2y$10$uzkKfUso9LE8MarvEAnWKOKmVSjE7ThaQGMIAd10aSdd2BZ7Ai2pq', '2024-12-07 21:40:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `usuario_administrador` (`usuario_administrador`),
  ADD UNIQUE KEY `correo_administrador` (`correo_administrador`),
  ADD KEY `idx_usuario` (`usuario_administrador`),
  ADD KEY `idx_correo` (`correo_administrador`);

--
-- Indexes for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indexes for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_producto` (`id_producto`),
  ADD KEY `detalle_pedido_ibfk_1` (`id_pedido`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_empleado` (`usuario_empleado`),
  ADD UNIQUE KEY `correo_empleado` (`correo_empleado`),
  ADD UNIQUE KEY `numero_placa` (`numero_placa`),
  ADD UNIQUE KEY `numero_cuenta` (`numero_cuenta`);

--
-- Indexes for table `estados_pedido`
--
ALTER TABLE `estados_pedido`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_restaurante` (`id_restaurante`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_restaurante` (`id_restaurante`);

--
-- Indexes for table `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo_restaurante` (`correo_restaurante`),
  ADD UNIQUE KEY `usuario_gerente` (`usuario_gerente`),
  ADD UNIQUE KEY `correo_gerente` (`correo_gerente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `estados_pedido`
--
ALTER TABLE `estados_pedido`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Constraints for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `fk_detalle_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `fk_detalle_pedido_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
