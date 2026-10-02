-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-09-2026 a las 23:01:39
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
-- Base de datos: `bibliotecatenjo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `boton1_texto` varchar(100) DEFAULT NULL,
  `boton1_url` varchar(255) DEFAULT NULL,
  `boton2_texto` varchar(100) DEFAULT NULL,
  `boton2_url` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `banners`
--

INSERT INTO `banners` (`id`, `titulo`, `descripcion`, `imagen`, `boton1_texto`, `boton1_url`, `boton2_texto`, `boton2_url`, `orden`, `estado`, `created_at`, `updated_at`) VALUES
(2, 'Prueba banner', 'Vamos a comprobar el cargue de un nuevo banner.', 'uploads/banners/banner_1788116105_6f095bb1.jpg', 'Prueba boton', 'http://localhost/bibliotecatenjo/public/index.html', NULL, NULL, 5, 1, '2026-08-30 18:55:05', '2026-09-22 18:15:47'),
(3, 'Nuevo banner', 'Prueba para el cargue del nuevo banner que aparece en el segundo lugar.', 'uploads/banners/banner_1788116635_bfcf5fa7.jpg', 'Servicios', 'http://localhost/bibliotecatenjo/public/servicios.html', NULL, NULL, 2, 1, '2026-08-30 19:03:55', '2026-08-30 19:03:55'),
(4, 'Prueba nuevo banner', 'kajdsiyabd{ksmdl{s kdpiHIbnpAMÑKand ñOSKJiahsiuaBS', 'uploads/banners/banner_1788182192_10d1c284.jpg', 'Noticias', 'http://localhost/bibliotecatenjo/public/noticias.html', NULL, NULL, 2, 1, '2026-08-31 13:16:32', '2026-09-22 18:15:59'),
(5, 'Banner nuevo', 'Banner de prueba de cargue', 'uploads/banners/banner_1788376718_7a9091d8.jpg', 'Noticias', 'http://localhost/bibliotecatenjo/public/noticias.html', NULL, NULL, 4, 1, '2026-09-02 19:18:38', '2026-09-02 19:18:38'),
(6, 'Nuevo Banner', 'Diplomado de prueba que dijo Juli', 'uploads/banners/banner_1790100821_48b774c4.jpg', 'Inscribete', 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=oGfaB0MfjE6Xf1-ItkcO5ob-G1IL_ElPo2bTR4vFvLpUN1JRUlZUUFJETkRST1QyTExHMzBPNFUwRi4u&route=shorturl', 'Eventos', 'http://localhost/bibliotecatenjo/public/eventos.html', 1, 1, '2026-09-22 18:13:41', '2026-09-22 18:13:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(180) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `asunto` varchar(180) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'nuevo',
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `correo_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `nombre`, `correo`, `telefono`, `asunto`, `mensaje`, `estado`, `ip_origen`, `user_agent`, `correo_enviado`, `created_at`, `updated_at`) VALUES
(1, 'Jenny Andrea', 'jennylda@gmail.com', '3156161111', 'Solicitud horarios', 'Me gustaria saber que horarios tiene la biblioteca el sabado y domingo', 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 0, '2026-09-01 01:53:04', '2026-09-01 01:53:04'),
(2, 'Julian Angel', 'juli9angel@hotmail.es', '3156161111', 'Prueba', 'Prueba descripcion del mensaje', 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 02:55:09', '2026-09-01 02:55:12'),
(3, 'Julian Angel', 'juli9angel@hotmail.es', '3156161111', 'Prueba envío mensajes de contacto', 'Prueba envío mensajes de contacto', 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 11:14:36', '2026-09-01 11:14:39'),
(4, 'Jenny Andrea', 'julian9angel@hotmail.es', '3156161111', 'Prueba 2', 'Prueba envío mensajes de contacto', 'revisado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 11:16:09', '2026-09-02 15:00:00'),
(5, 'Pepito Perez', 'nestorgoyes@gmail.com', '3156161111', 'Solicitud horarios', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-02 19:26:09', '2026-09-02 19:26:13'),
(6, 'Pepito Perez', 'julian9angel@hotmail.es', '3156161111', 'Prueba envío mensajes de contacto', 'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo \"Contenido aquí, contenido aquí\".', 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-23 13:47:25', '2026-09-23 13:47:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(220) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo` varchar(255) NOT NULL,
  `nombre_archivo_original` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(50) DEFAULT NULL,
  `tamano_archivo` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_documento` date DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `titulo`, `descripcion`, `archivo`, `nombre_archivo_original`, `tipo_archivo`, `tamano_archivo`, `fecha_documento`, `orden`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'RESOLUCIÓN No. 65 DEL 04 DE AGOSTO DE 2025 - REGLAMENTO BIBLIOTECA', 'Documento institucional disponible para consulta pública.', 'uploads/documentos/documento_1788137976_086a3102.pdf', 'RESOLUCIÓN 65 REGLAMENTO.pdf', 'PDF', 228402, '2025-08-04', 1, 1, '2026-08-31 00:59:36', '2026-09-22 18:18:08'),
(3, 'REGLAMENTO BIBLIOTECA', 'Conoce el reglamento de la biblioteca', 'uploads/documentos/documento_1790101178_a8579254.pdf', 'REGLAMENTO BIBLIOTECA ISABEL MURILLO DE LUQUE Y SU EXTENSIÓN IMCTT 2025 (1).pdf', 'PDF', 373064, '2025-08-04', 2, 1, '2026-09-22 18:19:38', '2026-09-22 18:19:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encabezados_vistas`
--

CREATE TABLE `encabezados_vistas` (
  `id` int(10) UNSIGNED NOT NULL,
  `vista` varchar(100) NOT NULL,
  `etiqueta` varchar(150) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encabezados_vistas`
--

INSERT INTO `encabezados_vistas` (`id`, `vista`, `etiqueta`, `titulo`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'sedes', 'Biblioteca en el territorio', 'Nuestras Sedes', 'Espacios que acercan la lectura, el conocimiento y la vida cultural a distintos puntos del territorio, fortaleciendo el vínculo entre la biblioteca y la comunidad.', 1, '2026-09-22 12:47:09', '2026-09-22 15:57:42'),
(2, 'servicios', 'Servicios para todos', 'Servicios Bibliotecarios', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 15:50:59'),
(3, 'actividades-transversales', 'Cultura Nuevo Titulo', 'Actividades Transversales Nuevo', 'xxxxx', 1, '2026-09-22 12:47:09', '2026-09-22 18:33:39'),
(4, 'programas', 'Programas para la comunidad', 'Programas bibliotecarios', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(5, 'eventos', 'Agenda cultural', 'Eventos del Mes de Octubre', 'Conoce la magia de la biblioteca a traves de nuestras actividades', 1, '2026-09-22 12:47:09', '2026-09-22 18:32:01'),
(6, 'coworking', 'Espacios para compartir', 'Solicitud de espacios', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(7, 'pqrs', 'Atención ciudadana', 'PQRSDF', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(8, 'documentacion', 'Información pública', 'Documentación pública', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(9, 'equipo', 'Nuestro equipo', 'Equipo de la biblioteca', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(10, 'contacto', 'Estamos para ayudarte', 'Contáctanos', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09'),
(11, 'material-digital', 'Lectura sin fronteras', 'Material digital', NULL, 1, '2026-09-22 12:47:09', '2026-09-22 12:47:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `descripcion`, `fecha_inicio`, `fecha_fin`, `categoria`, `imagen`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Cambio titulo', 'Este es un evento de prueba para validar el administrador.', '2026-09-15', '2026-09-15', 'Literatura y lectura', 'uploads/eventos/evento_1788103604_ba4fb98c.png', 1, '2026-08-30 02:22:49', '2026-08-30 16:14:01'),
(3, 'Prueba cargue Evento', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2026-09-02', '2026-09-15', 'Poesia', 'uploads/eventos/evento_1788376300_4bbc50e7.png', 1, '2026-09-02 19:11:40', '2026-09-02 19:11:40'),
(4, 'Diplomado de Gestión Cultural', 'Mejora tus herramientas de formación en cultura', '2026-09-22', '2026-10-01', 'Formación', 'uploads/eventos/evento_1790101726_9437401a.jpeg', 1, '2026-09-22 18:28:46', '2026-09-22 18:28:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `popup_home`
--

CREATE TABLE `popup_home` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `boton_texto` varchar(100) DEFAULT NULL,
  `boton_url` varchar(255) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `popup_home`
--

INSERT INTO `popup_home` (`id`, `titulo`, `descripcion`, `imagen`, `boton_texto`, `boton_url`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES
(2, 'Foro de Patrimonio', 'Espacio de formacion dirigido a quienes aman, conocen y trabajan por la protección del patrimonio.', 'uploads/popup/popup_1790101399_be51f3d1.jpeg', 'Eventos', 'http://localhost/bibliotecatenjo/public/eventos.html', '2026-09-22 10:27:00', '2026-09-26 21:27:00', 1, '2026-09-02 02:27:54', '2026-09-22 18:24:17'),
(3, 'Día de la Poesia!', 'Al contrario del pensamiento popular, el texto de Lorem Ipsum no es simplemente texto aleatorio. Tiene sus raices en una pieza cl´sica de la literatura del Latin, que data del año 45 antes de Cristo, haciendo que este adquiera mas de 2000 años de antiguedad. Richard McClintock, un profesor de Latin de la Universidad de Hampden-Sydney en Virginia, encontró una de las palabras más oscuras de la lengua del latín, \"consecteur\", en un pasaje de Lorem Ipsum, y al seguir leyendo distintos textos del latín, descubrió la fuente indudable.', 'uploads/popup/popup_1788316093_867bc36a.png', 'Eventos', 'http://localhost/bibliotecatenjo/public/eventos.html', '2026-09-23 12:20:00', '2026-09-23 12:20:00', 2, '2026-09-02 02:28:13', '2026-09-02 02:42:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pqrs`
--

CREATE TABLE `pqrs` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_solicitante` varchar(50) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `correo` varchar(180) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `tipo_pqrs` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `nombre_archivo_original` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(50) DEFAULT NULL,
  `tamano_archivo` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'nuevo',
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `correo_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pqrs`
--

INSERT INTO `pqrs` (`id`, `tipo_solicitante`, `nombre_completo`, `tipo_documento`, `numero_documento`, `correo`, `telefono`, `tipo_pqrs`, `descripcion`, `archivo`, `nombre_archivo_original`, `tipo_archivo`, `tamano_archivo`, `estado`, `ip_origen`, `user_agent`, `correo_enviado`, `created_at`, `updated_at`) VALUES
(1, 'ciudadano', 'Jenny Andrea', 'cc', '35355363', 'julian9angel@hotmail.es', '3156161111', 'felicitacion', 'Felicitación por su atención', 'uploads/pqrs/pqrs_1788262735_ff0470f3.png', 'flecha1.png', 'PNG', 674552, 'atendido', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 0, '2026-09-01 11:38:55', '2026-09-02 15:14:43'),
(2, 'estudiante', 'Emanuel Agudelo', 'ti', '1073444555', 'julian9angel@hotmail.es', '3156161111', 'sugerencia', 'Prueba de envio de pqrsdf', 'uploads/pqrs/pqrs_1788263244_5b3a8000.png', 'LOGO PORTAL NUEVO - ISOTIPO.png', 'PNG', 12843, 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 11:47:24', '2026-09-01 11:47:27'),
(3, 'estudiante', 'Samuel Angel', 'cc', '1073444555', 'julian9angel@hotmail.es', '3156161111', 'reclamo', 'Prueba envío pqrsdf', 'uploads/pqrs/pqrs_1788264493_a946e1a9.pdf', 'Gfourmis No. IE3633.pdf', 'PDF', 357173, 'nuevo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 12:08:13', '2026-09-01 12:08:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_espacios`
--

CREATE TABLE `solicitudes_espacios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `correo` varchar(180) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `espacio` varchar(80) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `horario` varchar(50) NOT NULL,
  `tipo_uso` varchar(50) NOT NULL,
  `cantidad_personas` varchar(20) NOT NULL,
  `detalle` text NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'pendiente',
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `correo_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_espacios`
--

INSERT INTO `solicitudes_espacios` (`id`, `nombre_completo`, `correo`, `telefono`, `espacio`, `fecha_reserva`, `horario`, `tipo_uso`, `cantidad_personas`, `detalle`, `estado`, `ip_origen`, `user_agent`, `correo_enviado`, `created_at`, `updated_at`) VALUES
(1, 'Andres Moreno', 'julian9angel@hotmail.com', '3156161111', 'sala-alas-papel', '2026-09-09', '4-6', 'estudio', '1', 'Solicito el espacio para trabajar', 'pendiente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 0, '2026-09-01 16:24:19', '2026-09-01 16:24:19'),
(2, 'Maria Rodriguez', 'julian9angel@hotmail.com', '3156161111', 'box-lectura', '2026-09-17', '12-2', 'estudio', '1', 'Solicito espacio de trabajo', 'rechazada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 16:31:41', '2026-09-02 15:30:43'),
(3, 'Emanuel Agudelo', 'julian9angel@hotmail.es', '3156161111', 'computadores', '2026-09-02', '8-10', 'trabajo', '2', 'Solicito espacio para trabajar.', 'pendiente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-09-01 16:36:10', '2026-09-01 16:36:12'),
(4, 'Julian Cortes', 'subdireccionculturatenjo@gmail.com', '3156161111', 'computadores', '2026-09-24', '8-10', 'estudio', '1', 'ggggggdfgdfgdfgdfgdfgdgdfgd', 'atendida', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 1, '2026-09-22 17:59:08', '2026-09-22 18:35:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'administrador',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `rol`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Biblioteca', 'admin@bibliotecatenjo.local', '$2y$10$MT8wPBJJs2m5KqXRCf6orOjn5SXfHlgbEyjYosp2FMEt0zs3TAW02', 'administrador', 1, '2026-08-29 19:58:56', '2026-08-29 19:58:56'),
(3, 'Usuario Prueba (editar)', 'julian9angel@hotmail.es', '$2y$10$d7x//50UTVqPzAjZGNh8puuxwySNg1lXuzpvGPIUpJTKPa.Sj0Hz6', 'administrador', 0, '2026-09-02 15:48:12', '2026-09-02 15:57:21');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encabezados_vistas`
--
ALTER TABLE `encabezados_vistas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vista` (`vista`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `popup_home`
--
ALTER TABLE `popup_home`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes_espacios`
--
ALTER TABLE `solicitudes_espacios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `encabezados_vistas`
--
ALTER TABLE `encabezados_vistas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `popup_home`
--
ALTER TABLE `popup_home`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes_espacios`
--
ALTER TABLE `solicitudes_espacios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
