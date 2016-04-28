-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 26 2016 г., 00:14
-- Версия сервера: 5.5.48
-- Версия PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Books`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL,
  `lastName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL,
  `filephotos_id` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `filebooks_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `books_rubrics`
--

CREATE TABLE IF NOT EXISTS `books_rubrics` (
  `book_id` int(11) NOT NULL,
  `rubric_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Filebook`
--

CREATE TABLE IF NOT EXISTS `Filebook` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mimeType` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(10,0) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Filebook`
--

INSERT INTO `Filebook` (`id`, `path`, `name`, `mimeType`, `size`) VALUES
(1, '/data/books/php35F3_571df8d4b8f8c.pdf', 'php35F3_571df8d4b8f8c.pdf', 'application/pdf', '758357'),
(2, '/data/books/php5F9E_571e02db5e20c.pdf', 'php5F9E_571e02db5e20c.pdf', 'application/pdf', '758357'),
(3, '/data/books/phpD8D2_571e033b6b090.pdf', 'phpD8D2_571e033b6b090.pdf', 'application/pdf', '646415'),
(4, '/data/books/php4219_571e039767be0.pdf', 'php4219_571e039767be0.pdf', 'application/pdf', '883732'),
(5, '/data/books/phpCB50_571e03baa2eaa.pdf', 'phpCB50_571e03baa2eaa.pdf', 'application/pdf', '666381'),
(6, '/data/books/php272A_571e04d8f3cd2.pdf', 'php272A_571e04d8f3cd2.pdf', 'application/pdf', '646415'),
(7, '/data/books/phpA8AB_571e053ac33ae.pdf', 'phpA8AB_571e053ac33ae.pdf', 'application/pdf', '646415'),
(8, '/data/books/php3CB0_571e05612f2db.pdf', 'php3CB0_571e05612f2db.pdf', 'application/pdf', '758357'),
(9, '/data/books/php3555_571e1a1b7b625.pdf', 'php3555_571e1a1b7b625.pdf', 'application/pdf', '666381'),
(10, '/data/books/php6825_571e1aab0c77b.pdf', 'php6825_571e1aab0c77b.pdf', 'application/pdf', '646415'),
(11, '/data/books/php43EC_571e1b24e62c0.pdf', 'php43EC_571e1b24e62c0.pdf', 'application/pdf', '758357'),
(12, '/data/books/php7358_571e1c3796ecb.pdf', 'php7358_571e1c3796ecb.pdf', 'application/pdf', '666381'),
(13, '/data/books/phpEF01_571e4759a9bce.pdf', 'phpEF01_571e4759a9bce.pdf', 'application/pdf', '646415'),
(14, '/data/books/phpAF81_571e4891162a8.pdf', 'phpAF81_571e4891162a8.pdf', 'application/pdf', '883732'),
(15, '/data/books/php8F8D_571e490cf3b6b.pdf', 'php8F8D_571e490cf3b6b.pdf', 'application/pdf', '666381'),
(16, '/data/books/phpDD98_571e4dbc50702.pdf', 'phpDD98_571e4dbc50702.pdf', 'application/pdf', '646415'),
(17, '/data/books/php402E_571e4e9a00a50.pdf', 'php402E_571e4e9a00a50.pdf', 'application/pdf', '758357'),
(18, '/data/books/php5DF4_571e4ee32d5fe.pdf', 'php5DF4_571e4ee32d5fe.pdf', 'application/pdf', '883732'),
(19, '/data/books/php3A73_571e5062ca1f6.pdf', 'php3A73_571e5062ca1f6.pdf', 'application/pdf', '883732'),
(20, '/data/books/php2138_571e5d6b046ad.pdf', 'php2138_571e5d6b046ad.pdf', 'application/pdf', '666381'),
(21, '/data/books/php4A2A_571e5db649cad.pdf', 'php4A2A_571e5db649cad.pdf', 'application/pdf', '883732'),
(22, '/data/books/php56A5_571e5e3d0a38e.pdf', 'php56A5_571e5e3d0a38e.pdf', 'application/pdf', '758357'),
(23, '/data/books/php1D75_571e65dc696a5.pdf', 'php1D75_571e65dc696a5.pdf', 'application/pdf', '758357'),
(24, '/data/books/php47AF_571e662880b78.pdf', 'php47AF_571e662880b78.pdf', 'application/pdf', '646415'),
(25, '/data/books/phpB846_571e6e34c7e0b.pdf', 'phpB846_571e6e34c7e0b.pdf', 'application/pdf', '666381'),
(26, '/data/books/php29AB_571e6e932895f.pdf', 'php29AB_571e6e932895f.pdf', 'application/pdf', '666381'),
(27, '/data/books/php2B68_571e6f17b1172.pdf', 'php2B68_571e6f17b1172.pdf', 'application/pdf', '758357'),
(28, '/data/books/phpA568_571e6f779c7da.pdf', 'phpA568_571e6f779c7da.pdf', 'application/pdf', '758357'),
(29, '/data/books/php49C6_571e70a814800.pdf', 'php49C6_571e70a814800.pdf', 'application/pdf', '883732'),
(30, '/data/books/php6143_571e7a27413ef.pdf', 'php6143_571e7a27413ef.pdf', 'application/pdf', '758357'),
(31, '/data/books/php535C_571e80cc36446.pdf', 'php535C_571e80cc36446.pdf', 'application/pdf', '883732'),
(32, '/data/books/php51AF_571e814d94d15.pdf', 'php51AF_571e814d94d15.pdf', 'application/pdf', '666381');

-- --------------------------------------------------------

--
-- Структура таблицы `Filephoto`
--

CREATE TABLE IF NOT EXISTS `Filephoto` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mimeType` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(10,0) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Filephoto`
--

INSERT INTO `Filephoto` (`id`, `path`, `name`, `mimeType`, `size`) VALUES
(1, '/data/img/php35B4_571df8d4a4f60.jpg', 'php35B4_571df8d4a4f60.jpg', 'image/jpeg', '32637'),
(2, '/data/img/php5F6E_571e02db4b2c9.jpg', 'php5F6E_571e02db4b2c9.jpg', 'image/jpeg', '26900'),
(3, '/data/img/phpD893_571e033b5530d.jpg', 'phpD893_571e033b5530d.jpg', 'image/jpeg', '28899'),
(4, '/data/img/php41CA_571e039751133.jpg', 'php41CA_571e039751133.jpg', 'image/jpeg', '39348'),
(5, '/data/img/phpCB20_571e03ba8eda6.jpg', 'phpCB20_571e03ba8eda6.jpg', 'image/jpeg', '26900'),
(6, '/data/img/php26FA_571e04d8df3f8.jpg', 'php26FA_571e04d8df3f8.jpg', 'image/jpeg', '26900'),
(7, '/data/img/phpA86C_571e053ab04ec.jpg', 'phpA86C_571e053ab04ec.jpg', 'image/jpeg', '39033'),
(8, '/data/img/php3C71_571e05611c76e.jpg', 'php3C71_571e05611c76e.jpg', 'image/jpeg', '30581'),
(9, '/data/img/php34B8_571e1a1b678bd.jpg', 'php34B8_571e1a1b678bd.jpg', 'image/jpeg', '28899'),
(10, '/data/img/php6788_571e1aaaece84.jpg', 'php6788_571e1aaaece84.jpg', 'image/jpeg', '26900'),
(11, '/data/img/php439D_571e1b24d0b49.jpg', 'php439D_571e1b24d0b49.jpg', 'image/jpeg', '39348'),
(12, '/data/img/php72F9_571e1c3782b23.jpg', 'php72F9_571e1c3782b23.jpg', 'image/jpeg', '39033'),
(13, '/data/img/phpEEB2_571e4759958d2.jpg', 'phpEEB2_571e4759958d2.jpg', 'image/jpeg', '32637'),
(14, '/data/img/phpAF41_571e489102187.jpg', 'phpAF41_571e489102187.jpg', 'image/jpeg', '32637'),
(15, '/data/img/php8F4E_571e490ce0ee1.jpg', 'php8F4E_571e490ce0ee1.jpg', 'image/jpeg', '39033'),
(16, '/data/img/phpDD59_571e4dbc3cef7.jpg', 'phpDD59_571e4dbc3cef7.jpg', 'image/jpeg', '39348'),
(17, '/data/img/php3FEE_571e4e99e1860.jpg', 'php3FEE_571e4e99e1860.jpg', 'image/jpeg', '30581'),
(18, '/data/img/php5DC4_571e4ee2cfa81.jpg', 'php5DC4_571e4ee2cfa81.jpg', 'image/jpeg', '28899'),
(19, '/data/img/php3A43_571e5062b7840.jpg', 'php3A43_571e5062b7840.jpg', 'image/jpeg', '26900'),
(20, '/data/img/php20F9_571e5d6ae507a.jpg', 'php20F9_571e5d6ae507a.jpg', 'image/jpeg', '39033'),
(21, '/data/img/php49EA_571e5db6356d0.jpg', 'php49EA_571e5db6356d0.jpg', 'image/jpeg', '39033'),
(22, '/data/img/php5676_571e5e3cebe1c.jpg', 'php5676_571e5e3cebe1c.jpg', 'image/jpeg', '28899'),
(23, '/data/img/php1D45_571e65dc4f993.jpg', 'php1D45_571e65dc4f993.jpg', 'image/jpeg', '39348'),
(24, '/data/img/php477F_571e66286c171.jpg', 'php477F_571e66286c171.jpg', 'image/jpeg', '30581'),
(25, '/data/img/phpB7D8_571e6e34b45e7.jpg', 'phpB7D8_571e6e34b45e7.jpg', 'image/jpeg', '39033'),
(26, '/data/img/php296B_571e6e931542a.jpg', 'php296B_571e6e931542a.jpg', 'image/jpeg', '28899'),
(27, '/data/img/php2B29_571e6f1798bec.jpg', 'php2B29_571e6f1798bec.jpg', 'image/jpeg', '30581'),
(28, '/data/img/phpA519_571e6f77894e4.jpg', 'phpA519_571e6f77894e4.jpg', 'image/jpeg', '28899'),
(29, '/data/img/php4986_571e70a7b3daa.jpg', 'php4986_571e70a7b3daa.jpg', 'image/jpeg', '32637'),
(30, '/data/img/php6104_571e7a272d1d6.jpg', 'php6104_571e7a272d1d6.jpg', 'image/jpeg', '39033'),
(31, '/data/img/php531D_571e80cc22bf8.jpg', 'php531D_571e80cc22bf8.jpg', 'image/jpeg', '39033'),
(32, '/data/img/php5170_571e814d81427.jpg', 'php5170_571e814d81427.jpg', 'image/jpeg', '39033');

-- --------------------------------------------------------

--
-- Структура таблицы `rubrics`
--

CREATE TABLE IF NOT EXISTS `rubrics` (
  `id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$14$V4zQtYztgtrnZly/YL7Z8uDAP82Nd1DU48l6uj4NAlVMfOBai1dAy', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4A1B2A9252F6F466` (`filebooks_id`),
  ADD UNIQUE KEY `UNIQ_4A1B2A929C91269F` (`filephotos_id`),
  ADD KEY `IDX_4A1B2A92F675F31B` (`author_id`);

--
-- Индексы таблицы `books_rubrics`
--
ALTER TABLE `books_rubrics`
  ADD PRIMARY KEY (`book_id`,`rubric_id`),
  ADD KEY `IDX_5618A0EA16A2B381` (`book_id`),
  ADD KEY `IDX_5618A0EAA29EC0FC` (`rubric_id`);

--
-- Индексы таблицы `Filebook`
--
ALTER TABLE `Filebook`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Filephoto`
--
ALTER TABLE `Filephoto`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rubrics`
--
ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5F6A26792B36786B` (`title`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `Filebook`
--
ALTER TABLE `Filebook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `Filephoto`
--
ALTER TABLE `Filephoto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `rubrics`
--
ALTER TABLE `rubrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `FK_4A1B2A9252F6F466` FOREIGN KEY (`filebooks_id`) REFERENCES `Filebook` (`id`),
  ADD CONSTRAINT `FK_4A1B2A929C91269F` FOREIGN KEY (`filephotos_id`) REFERENCES `Filephoto` (`id`),
  ADD CONSTRAINT `FK_4A1B2A92F675F31B` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `books_rubrics`
--
ALTER TABLE `books_rubrics`
  ADD CONSTRAINT `FK_5618A0EA16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5618A0EAA29EC0FC` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
