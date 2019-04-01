-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 01 2019 г., 09:09
-- Версия сервера: 5.6.38
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `subscribers.local`
--

-- --------------------------------------------------------

--
-- Структура таблицы `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190330204753', '2019-03-30 20:48:39');

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `published_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `author_id`, `title`, `content`, `published_at`) VALUES
(1, 5, 'Post0', 'Post0 content', '2019-03-30 23:50:43'),
(2, 5, 'Post0', 'Post0 content', '2019-03-30 23:50:58'),
(3, 1, 'Post1', 'Post0 content', '2019-03-30 23:52:22'),
(4, 1, 'Post2', 'Post2 content2', '2019-03-30 23:52:31'),
(5, 1, 'Post3', 'Post3 content', '2019-03-30 23:52:40'),
(6, 3, 'Post4', 'Post4 Content', '2019-03-30 23:52:40');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'first@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$c3BHQWxrRS9yN29ZOTA4cA$Q07kslGhGROWu9Kx0Oz4BREZFjObwqyfLEyZKnNCA+A'),
(2, 'second@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$VVRPeGF2L3N0ay5WRG5KWg$UxvD9pr2BfA4v6EC2hu7NAEJlQBvMinAF29VC9bgg6k'),
(3, 'third@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$TE5FWTROVExMSEV3a0pRdg$UIdJMgghF7Z/LGBO7WUycKvjKamZnjKR3GgOqug69gc'),
(4, 'four@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$dS9VMC5NenBDTG01UW9udA$PNMTQEpr6e//QmucvdIxv9RelZ659B6UnN7PzOM9Tg0'),
(5, 'five@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$V3Fsa1NhLzliVnFObUZQWA$068MSMmcefQg9X+mU2SzSVsTTjuMr9+3pSS2W5G4ktw'),
(6, 'eight@g.g', '[]', '$argon2i$v=19$m=1024,t=2,p=2$bXhqaGIvVWR5aS5ZV0ZFMg$wg6s2bpkNGmN4HZEPX+TrVPBawB20fWLGS+/MUgRcck'),
(7, 'nine@g.g', '[\"ROLE_USER\"]', '$argon2i$v=19$m=1024,t=2,p=2$VXFFRjFmUWxBcnl4T1Z2Qw$45VRn6T3lChkoxXv9iKriw4+/+R2LkS8C2SR7PNPKU0');

-- --------------------------------------------------------

--
-- Структура таблицы `user_subscriber`
--

CREATE TABLE `user_subscriber` (
  `user_id` int(11) NOT NULL,
  `subscription_on_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_subscriber`
--

INSERT INTO `user_subscriber` (`user_id`, `subscription_on_id`) VALUES
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 3),
(2, 4),
(2, 5),
(6, 1),
(6, 3),
(7, 5);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8A6C8DF675F31B` (`author_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Индексы таблицы `user_subscriber`
--
ALTER TABLE `user_subscriber`
  ADD PRIMARY KEY (`user_id`,`subscription_on_id`),
  ADD KEY `IDX_A679D85A76ED395` (`user_id`),
  ADD KEY `IDX_A679D8565818AE0` (`subscription_on_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8DF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_subscriber`
--
ALTER TABLE `user_subscriber`
  ADD CONSTRAINT `FK_A679D8565818AE0` FOREIGN KEY (`subscription_on_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A679D85A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
