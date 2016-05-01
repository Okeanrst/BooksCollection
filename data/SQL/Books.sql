SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL,
  `lastName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL,
  `filephotos_id` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `filebooks_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `books_rubrics` (
  `book_id` int(11) NOT NULL,
  `rubric_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `Filebook` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mimeType` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `Filephoto` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mimeType` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `rubrics` (
  `id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$14$V4zQtYztgtrnZly/YL7Z8uDAP82Nd1DU48l6uj4NAlVMfOBai1dAy', NULL);

ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4A1B2A9252F6F466` (`filebooks_id`),
  ADD UNIQUE KEY `UNIQ_4A1B2A929C91269F` (`filephotos_id`),
  ADD KEY `IDX_4A1B2A92F675F31B` (`author_id`);

ALTER TABLE `books_rubrics`
  ADD PRIMARY KEY (`book_id`,`rubric_id`),
  ADD KEY `IDX_5618A0EA16A2B381` (`book_id`),
  ADD KEY `IDX_5618A0EAA29EC0FC` (`rubric_id`);

ALTER TABLE `Filebook`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Filephoto`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5F6A26792B36786B` (`title`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Filebook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Filephoto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rubrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `books`
  ADD CONSTRAINT `FK_4A1B2A9252F6F466` FOREIGN KEY (`filebooks_id`) REFERENCES `Filebook` (`id`),
  ADD CONSTRAINT `FK_4A1B2A929C91269F` FOREIGN KEY (`filephotos_id`) REFERENCES `Filephoto` (`id`),
  ADD CONSTRAINT `FK_4A1B2A92F675F31B` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

ALTER TABLE `books_rubrics`
  ADD CONSTRAINT `FK_5618A0EAA29EC0FC` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics` (`id`),
  ADD CONSTRAINT `FK_5618A0EA16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
