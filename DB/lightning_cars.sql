-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 13 2025 г., 09:15
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lightning_cars`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `seats` tinyint(4) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`car_id`, `name`, `year`, `price`, `description`, `is_available`, `image_url`, `seats`, `created_at`, `updated_at`) VALUES
(1, 'Tesla Model S', 2023, 89990.00, 'Long Range AWD, 396 miles range, 0-60 mph in 3.1s', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-11 17:56:51'),
(2, 'Porsche Taycan', 2023, 82900.00, 'Base model, 225 miles range, 0-60 in 5.1s', 0, NULL, 5, '2025-05-11 17:56:51', '2025-05-12 02:56:22'),
(3, 'Audi e-tron GT', 2023, 99901.00, 'Dual motor, 238 miles range, 0-60 in 3.9s', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-12 02:22:49'),
(4, 'Ford Mustang Mach-E', 2023, 46995.00, 'Standard Range RWD, 247 miles range', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-11 17:56:51'),
(5, 'Hyundai Ioniq 5', 2023, 41450.00, 'SE Standard Range, 220 miles range', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-11 17:56:51'),
(6, 'Rivian R1T', 2023, 73500.00, 'Adventure Package, 314 miles range', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-11 17:56:51'),
(7, 'Lucid Air', 2023, 77400.00, 'Pure trim, 410 miles range', 0, NULL, 5, '2025-05-11 17:56:51', '2025-05-12 02:20:44'),
(8, 'BMW i4', 2023, 51900.00, 'eDrive35, 260 miles range', 1, NULL, 5, '2025-05-11 17:56:51', '2025-05-11 17:56:51');

-- --------------------------------------------------------

--
-- Структура таблицы `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `car_id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 11, 3, '2025-05-22', NULL, 'pending', '2025-05-11 18:58:53'),
(2, 13, 7, '2025-05-21', NULL, 'pending', '2025-05-12 02:19:52'),
(3, 14, 2, '2025-05-22', NULL, 'confirmed', '2025-05-12 02:53:15');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `is_active`, `is_admin`) VALUES
(11, 'admin', '$2y$10$cElt/KQYjXxGQ8GLxUmRkOmR87Ca//TD73tCcletB1.8k3Ux2QZIa', 'admin@adminmail.com', '2025-05-11 16:46:02', 1, 1),
(12, 'admin1', '$2y$10$RjyVl7jrJ3jNs1lBaL/95excCypBz0fN98sP73NtQ63MErRCrZNSy', 'admin@admin1mail.com', '2025-05-11 21:18:35', 1, 0),
(13, 'neadmin', '$2y$10$Jj4VClTk9JXR6jrIt9byH.cBAEvx7s1nomAa8BaRqibwnpXDSI0aC', 'Neadmin1@admin.ru', '2025-05-12 02:18:22', 1, 0),
(14, 'admin44', '$2y$10$NdtyocWP/ba7/22NbIUxXOWL6fGRqVztS.hbW4UhE2JcNW23GNyCu', 'admin44@admin.com', '2025-05-12 02:52:37', 1, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`);

--
-- Индексы таблицы `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
