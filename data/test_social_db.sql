-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 06 2021 г., 15:48
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `social_db`
--

CREATE DATABASE IF NOT EXISTS `social_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `social_db`;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int(11) UNSIGNED NOT NULL,
  `gender` tinyint(1) UNSIGNED NOT NULL,
  `interests` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `surname`, `age`, `gender`, `interests`, `city`) VALUES
(1, 'user1', '$2y$10$Mlgqf5TvIxmfz89czCz9cOvK/SRRHdg9lLYedG5SA72eGUmPRH7jW', 'Андрей', 'Иванов', 29, 1, 'Интернет, и тд', 'Санкт-Петербург'),
(2, 'user2', '$2y$10$0H1nNRO.Gn8GK7sW1qoJa.nnXxm4keKl9.0O8QbfVrA.3pS3WUAK2', 'Алексей', 'Петров', 30, 1, 'Разное', 'Архангельск'),
(3, 'user3', '$2y$10$WrqO5090b3xR6VtjZ0RwAeSFsPebhuvhiQEX1ercdzAyfJ8P093RK', 'Иван', 'Сидоров', 31, 1, 'Сми', 'Москва');

-- --------------------------------------------------------

--
-- Структура таблицы `user_user`
--

CREATE TABLE `user_user` (
  `user_from` int(11) UNSIGNED NOT NULL,
  `user_to` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_user`
--

INSERT INTO `user_user` (`user_from`, `user_to`) VALUES
(1, 3),
(1, 2);

--
-- Структура таблицы `users`
--

CREATE TABLE `testtable` (
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
