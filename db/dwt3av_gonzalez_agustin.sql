-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2025 a las 06:53:43
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
-- Base de datos: `dwt3av_gonzalez_agustin`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'iPhone'),
(2, 'MacBook'),
(3, 'Reloj');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `compra_id` int(10) UNSIGNED NOT NULL,
  `usuarios_usuario_id` int(10) UNSIGNED NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `total_precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`compra_id`, `usuarios_usuario_id`, `fecha_compra`, `total_precio`) VALUES
(1, 3, '2025-12-13 20:47:48', 4739498.00),
(2, 3, '2025-12-16 10:36:20', 1020000.00),
(3, 3, '2025-12-16 10:36:34', 899900.00),
(4, 3, '2025-12-16 10:43:36', 899900.00),
(5, 4, '2025-12-17 06:28:12', 7538794.00),
(6, 3, '2025-12-17 22:34:53', 899900.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_productos`
--

CREATE TABLE `compra_productos` (
  `compra_productos_id` int(10) UNSIGNED NOT NULL,
  `compra_fk` int(10) UNSIGNED NOT NULL,
  `producto_fk` int(10) UNSIGNED NOT NULL,
  `cantidad_productos` tinyint(3) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra_productos`
--

INSERT INTO `compra_productos` (`compra_productos_id`, `compra_fk`, `producto_fk`, `cantidad_productos`) VALUES
(1, 1, 1, 1),
(2, 1, 7, 1),
(3, 1, 11, 1),
(4, 1, 14, 1),
(5, 4, 1, 1),
(6, 5, 6, 1),
(7, 5, 7, 1),
(8, 5, 12, 1),
(9, 5, 14, 5),
(10, 6, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_publicacion`
--

CREATE TABLE `estados_publicacion` (
  `estado_publicacion_id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_publicacion`
--

INSERT INTO `estados_publicacion` (`estado_publicacion_id`, `nombre`) VALUES
(1, 'Borrador'),
(2, 'Publicada'),
(3, 'Deshabilitada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `etiquetas`
--

INSERT INTO `etiquetas` (`id`, `nombre`) VALUES
(1, 'Oferta'),
(2, 'Nuevo'),
(3, 'Destacado'),
(4, 'Envio Gratis');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(10) UNSIGNED NOT NULL,
  `categoria_fk` int(10) UNSIGNED NOT NULL,
  `img` varchar(256) NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 100,
  `descripcion` text NOT NULL,
  `estado_publicacion_fk` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `categoria_fk`, `img`, `nombre`, `precio`, `stock`, `descripcion`, `estado_publicacion_fk`) VALUES
(1, 1, 'iphone-11-pro.png', 'iPhone 11 Pro', 899900.00, 100, 'Descubre el secreto de una piel radiante con nuestro Serum Rejuvenecedor. Formulado con ingredientes naturales y tecnología avanzada, este serum está diseñado para revitalizar y restaurar la luminosidad de tu piel. Su fórmula única combate los signos del envejecimiento, reduciendo líneas finas y mejorando la elasticidad. Ideal para todo tipo de piel, este serum es tu aliado diario para una apariencia más joven y saludable.', 2),
(2, 1, 'iphone-12-black.png', 'iPhone 12', 1020000.00, 100, 'El iPhone 12 es un smartphone de Apple lanzado en octubre de 2020. Presenta un diseño elegante con bordes planos y una pantalla Super Retina XDR de 6.1 pulgadas. Con el potente chip A14 Bionic, el iPhone 12 ofrece un rendimiento excepcional y capacidades avanzadas en fotografía y video. Su sistema de cámaras dual permite tomar fotos impresionantes en diversas condiciones de luz y graba videos en 4K, lo que lo convierte en una opción ideal para quienes buscan calidad y rendimiento en un dispositivo móvil.', 2),
(3, 1, 'iphone-13.png', 'iPhone 13 Max', 1499999.00, 100, 'El iPhone 13 Max, lanzado en septiembre de 2021, es un smartphone de Apple que destaca por su potente rendimiento y mejoras en la cámara. Con una pantalla Super Retina XDR de 6.1 pulgadas, ofrece una experiencia visual excepcional. El chip A15 Bionic permite un rendimiento más rápido y eficiente, mientras que el sistema de cámaras dual incluye mejoras significativas en fotografía con poca luz y grabación de video. El iPhone 13 Max es ideal para quienes buscan un dispositivo con tecnología avanzada y una duración de batería mejorada.', 2),
(4, 1, 'iphone-14.png', 'iPhone 14 Pro', 1599999.00, 100, 'El iPhone 14 Pro, lanzado en septiembre de 2022, es el modelo premium de Apple que destaca por su innovador diseño y potente rendimiento. Con una pantalla Super Retina XDR de 6.1 pulgadas y ProMotion, ofrece una experiencia visual fluida y vibrante. Equipado con el chip A16 Bionic, este dispositivo proporciona un rendimiento excepcional y una eficiencia energética mejorada. Su sistema de cámaras de triple lente permite capturar imágenes y videos de calidad profesional, incluyendo el nuevo Modo Cinemático para grabaciones en video de gran profundidad de campo.', 2),
(5, 1, 'iphone-x-black.png', 'iPhone X', 720000.00, 100, 'El iPhone X, lanzado en noviembre de 2017, marcó el décimo aniversario de Apple y presentó un diseño completamente renovado con una pantalla OLED de borde a borde. Con su pantalla Super Retina de 5.8 pulgadas, ofrece colores vibrantes y un contraste impresionante. Este modelo fue el primero en incorporar Face ID, permitiendo un desbloqueo seguro y fácil. Con el chip A11 Bionic, el iPhone X brinda un rendimiento sólido, y su sistema de cámaras dual de 12 MP permite tomar fotos de alta calidad, incluso en condiciones de poca luz.', 2),
(6, 2, 'note1.png', 'MacBook Air 13', 3349900.00, 100, 'La MacBook Air 13, lanzada en 2020, es una de las computadoras portátiles más ligeras y delgadas de Apple, diseñada para ofrecer un rendimiento sólido y eficiencia energética. Con su pantalla Retina de 13.3 pulgadas, proporciona imágenes nítidas y vibrantes. Impulsada por el chip M1 de Apple, ofrece un rendimiento excepcional en tareas cotidianas y una duración de batería impresionante, lo que la convierte en una excelente opción para estudiantes y profesionales en movimiento. Su diseño elegante y portátil la hace ideal para llevar a cualquier lugar.', 2),
(7, 2, 'note2.png', 'MacBook Air 14', 3649900.00, 100, 'La MacBook Air 14, lanzada en 2022, es una computadora portátil ultradelgada y ligera de Apple, diseñada para ofrecer un rendimiento excepcional en un formato compacto. Con su pantalla Liquid Retina de 14 pulgadas, proporciona colores vibrantes y un excelente nivel de detalle. Equipado con el chip M2 de Apple, ofrece un rendimiento impresionante y eficiencia energética, ideal para tareas diarias y profesionales. La MacBook Air 14 también cuenta con una duración de batería sobresaliente, lo que la convierte en una opción ideal para usuarios en movimiento.', 2),
(8, 2, 'note3.png', 'MacBook Pro 16', 3499900.00, 100, 'La MacBook Pro 16, lanzada en 2021, es una potente computadora portátil diseñada para profesionales y creadores de contenido. Con su pantalla Liquid Retina XDR de 16.2 pulgadas, ofrece una calidad de imagen excepcional con colores precisos y un alto rango dinámico. Equipado con el chip M1 Pro o M1 Max, proporciona un rendimiento sobresaliente en tareas intensivas, como edición de video y diseño gráfico. Su duración de batería también es impresionante, permitiendo hasta 21 horas de uso continuo, lo que la convierte en la opción ideal para aquellos que requieren potencia y portabilidad.', 2),
(9, 2, 'note4.png', 'MacBook Pro 14', 3099000.00, 100, 'La MacBook Pro 14, lanzada en 2021, es una potente laptop diseñada para satisfacer las necesidades de los profesionales creativos y técnicos. Con su pantalla Liquid Retina XDR de 14.2 pulgadas, ofrece una calidad de imagen impresionante, ideal para la edición de video, diseño gráfico y más. Equipado con el chip M1 Pro o M1 Max, proporciona un rendimiento rápido y eficiente en tareas exigentes. Además, su batería dura hasta 17 horas, lo que permite trabajar durante todo el día sin necesidad de cargarla.', 2),
(10, 2, 'note5.png', 'MacBook Pro 13', 2199900.00, 100, 'La MacBook Pro 13, lanzada en 2020, es una computadora portátil ideal para quienes buscan un equilibrio entre potencia y portabilidad...', 2),
(11, 3, 'reloj1.png', 'Reloj Montreal Negro', 101699.00, 100, 'El Reloj Montreal Negro es una pieza elegante y sofisticada que combina estilo y funcionalidad. Su diseño minimalista presenta una caja de acero inoxidable y una esfera negra que se complementa con detalles plateados...', 2),
(12, 3, 'reloj2.png', 'Reloj Montreal Acero', 98999.00, 100, 'El Reloj Montreal Acero es una elegante y duradera opción para quienes buscan un accesorio sofisticado y versátil. Su diseño clásico cuenta con una caja de acero inoxidable brillante y una esfera plateada...', 2),
(13, 3, 'reloj3.png', 'Reloj Montreal Oro', 105999.00, 100, 'El Reloj Montreal Oro es la opción perfecta para quienes buscan un accesorio llamativo y lujoso. Su diseño destaca por su elegante caja de acero inoxidable chapada en oro y una esfera dorada...', 2),
(14, 3, 'reloj4.png', 'Reloj Montreal Gris', 87999.00, 100, 'El Reloj Montreal Gris es una opción moderna y elegante que combina estilo y funcionalidad...', 2),
(15, 3, 'reloj5.png', 'Reloj Montreal Rosa', 101699.00, 100, 'El Reloj Montreal Rosa es un accesorio delicado y moderno, perfecto para quienes buscan un toque de color sin perder elegancia...', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_x_etiquetas`
--

CREATE TABLE `productos_x_etiquetas` (
  `id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `etiqueta_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_x_etiquetas`
--

INSERT INTO `productos_x_etiquetas` (`id`, `producto_id`, `etiqueta_id`) VALUES
(8, 7, 1),
(9, 13, 1),
(12, 14, 1),
(13, 14, 4),
(14, 15, 2),
(17, 6, 4),
(20, 1, 1),
(21, 1, 3),
(22, 2, 3),
(23, 4, 2),
(24, 4, 3),
(25, 8, 2),
(26, 11, 2),
(27, 3, 3),
(28, 5, 1),
(29, 5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `nombre`) VALUES
(1, 'Superadministrador'),
(2, 'Administrador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(256) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `rol_fk` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `username`, `email`, `password`, `rol_fk`) VALUES
(1, 'superadmin', 'superadmin@gonzaltech.com', '$2y$10$Ha5njlqc18Y4UBiUKVjupu0MjBw9uxGLxN.M93uQlCIMYK.J4tHqK', 1),
(2, 'admin', 'admin@gonzaltech.com', '$2y$10$Ha5njlqc18Y4UBiUKVjupu0MjBw9uxGLxN.M93uQlCIMYK.J4tHqK', 2),
(3, 'Agustin', 'agustin@correo.com', '$2y$10$q5qpeZK.GtlAUT/MmONYrukltkhUd84bZela4eAbhBIb191oWx4lK', 3),
(4, 'Alejandro', 'alejandro@correo.com', '$2y$10$cuHShz57A/38mZnkMtDCYObZffnjY3SOAUdybm2jt0rGFhGuc1BlK', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`compra_id`),
  ADD KEY `fk_compra_usuarios1_idx` (`usuarios_usuario_id`);

--
-- Indices de la tabla `compra_productos`
--
ALTER TABLE `compra_productos`
  ADD PRIMARY KEY (`compra_productos_id`),
  ADD KEY `fk_compra_has_productos_productos1_idx` (`producto_fk`),
  ADD KEY `fk_compra_has_productos_compra1_idx` (`compra_fk`);

--
-- Indices de la tabla `estados_publicacion`
--
ALTER TABLE `estados_publicacion`
  ADD PRIMARY KEY (`estado_publicacion_id`);

--
-- Indices de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `fk_productos_estados_publicacion1_idx` (`estado_publicacion_fk`),
  ADD KEY `fk_productos_categorias` (`categoria_fk`);

--
-- Indices de la tabla `productos_x_etiquetas`
--
ALTER TABLE `productos_x_etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `etiqueta_id` (`etiqueta_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_usuarios_roles_idx` (`rol_fk`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `compra_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `compra_productos`
--
ALTER TABLE `compra_productos`
  MODIFY `compra_productos_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estados_publicacion`
--
ALTER TABLE `estados_publicacion`
  MODIFY `estado_publicacion_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `productos_x_etiquetas`
--
ALTER TABLE `productos_x_etiquetas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fk_compra_usuarios1` FOREIGN KEY (`usuarios_usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra_productos`
--
ALTER TABLE `compra_productos`
  ADD CONSTRAINT `fk_compra_has_productos_compra1` FOREIGN KEY (`compra_fk`) REFERENCES `compra` (`compra_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_compra_has_productos_productos1` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`categoria_fk`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_productos_estados_publicacion1` FOREIGN KEY (`estado_publicacion_fk`) REFERENCES `estados_publicacion` (`estado_publicacion_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_x_etiquetas`
--
ALTER TABLE `productos_x_etiquetas`
  ADD CONSTRAINT `productos_x_etiquetas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_x_etiquetas_ibfk_2` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_fk`) REFERENCES `roles` (`rol_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
