-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2020 a las 20:56:57
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdcementerios`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vresumencuotas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vresumencuotas` (
`idcontrato` int(11)
,`totalmonto` decimal(33,2)
,`totalsaldo` decimal(33,2)
,`totalacuenta` decimal(33,2)
,`cancuotas` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vresumencuotas`
--
DROP TABLE IF EXISTS `vresumencuotas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vresumencuotas`  AS  select `cuota`.`idcontrato` AS `idcontrato`,sum(`cuota`.`monto`) AS `totalmonto`,sum(`cuota`.`saldo`) AS `totalsaldo`,sum(`cuota`.`acuenta`) AS `totalacuenta`,count(`cuota`.`idcontrato`) AS `cancuotas` from `cuota` group by `cuota`.`idcontrato` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
