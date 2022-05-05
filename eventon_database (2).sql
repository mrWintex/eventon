-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Stř 04. kvě 2022, 17:37
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `eventon_database`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `posts`
--

CREATE TABLE `posts` (
  `id_p` int(11) NOT NULL,
  `src` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `comment` text COLLATE utf8mb4_czech_ci NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `posts`
--

INSERT INTO `posts` (`id_p`, `src`, `comment`, `add_date`, `user_owner`) VALUES
(38, './users/1/_108857562_mediaitem108857561.jpg', 'le papa', '2022-04-21 20:14:41', 1),
(40, './users/5/STanager-Shapiro-ML.jpg', 'le birdy', '2022-04-27 20:39:34', 5),
(41, './users/57/Blue-Jay-on-redbud-tree-by-Tom-Reichner_news.png', 'le birdo', '2022-04-27 20:41:27', 57),
(42, './users/57/photo-1584315059202-5daeb3cf2a22.jpg', 'barvičky', '2022-04-26 20:42:10', 57),
(43, './users/5/a.jpg', 'le nature', '2022-04-25 20:43:19', 5),
(44, './users/5/photo-1624133310294-79f57f3ad50b.jpg', 'red birdo', '2022-04-23 20:43:37', 5),
(45, './users/1/puffin-4146015_960_720.jpg', 'le 2 birdo', '2022-04-20 20:48:05', 1),
(48, 'users/5/kingfisher-2046453_960_720.jpg', 'Ledňáček', '2022-05-03 05:11:00', 5),
(49, 'users/60/UK_wildbirds-01-robin.jpg', 'Birdo le pepe', '2022-05-03 07:00:47', 60),
(51, 'users/1/photo-1551085254-e96b210db58a.jpg', 'Tukan', '2022-05-03 07:26:06', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `posts_likes`
--

CREATE TABLE `posts_likes` (
  `user` int(11) NOT NULL,
  `post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `posts_likes`
--

INSERT INTO `posts_likes` (`user`, `post`) VALUES
(1, 41),
(1, 42),
(1, 43),
(1, 45),
(1, 48),
(5, 38),
(5, 42),
(5, 43),
(5, 44),
(5, 48),
(57, 38),
(58, 38),
(58, 44),
(60, 38),
(60, 44);

-- --------------------------------------------------------

--
-- Struktura tabulky `tags`
--

CREATE TABLE `tags` (
  `id_t` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `tag_post`
--

CREATE TABLE `tag_post` (
  `post` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id_u` int(11) NOT NULL,
  `username` varchar(18) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_czech_ci NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id_u`, `username`, `email`, `password`, `reg_date`, `admin`) VALUES
(1, 'Wintex', 'vitekprokes@gmail.com', '800e808cac6a1fa75b83e82559cd231d6ecb522b777ac91566d87b3eff24b50e', '2022-04-27 20:13:44', 1),
(5, 'user1', 'user1@user.cz', '800e808cac6a1fa75b83e82559cd231d6ecb522b777ac91566d87b3eff24b50e', '2022-04-27 19:08:19', 0),
(57, 'user2', 'user2@user.cz', '800e808cac6a1fa75b83e82559cd231d6ecb522b777ac91566d87b3eff24b50e', '2022-04-27 20:41:10', 0),
(58, 'user3', 'user3@user.cz', '800e808cac6a1fa75b83e82559cd231d6ecb522b777ac91566d87b3eff24b50e', '2022-05-01 19:10:12', 0),
(60, 'user6', 'user6@user.cz', '800e808cac6a1fa75b83e82559cd231d6ecb522b777ac91566d87b3eff24b50e', '2022-05-02 19:52:21', 0);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_p`),
  ADD UNIQUE KEY `src` (`src`),
  ADD UNIQUE KEY `comment` (`comment`) USING HASH,
  ADD UNIQUE KEY `comment_2` (`comment`) USING HASH,
  ADD KEY `posts_ibfk_1` (`user_owner`);

--
-- Indexy pro tabulku `posts_likes`
--
ALTER TABLE `posts_likes`
  ADD PRIMARY KEY (`user`,`post`),
  ADD KEY `posts_likes_ibfk_1` (`post`);

--
-- Indexy pro tabulku `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_t`);

--
-- Indexy pro tabulku `tag_post`
--
ALTER TABLE `tag_post`
  ADD PRIMARY KEY (`post`,`tag`),
  ADD KEY `tag_post_ibfk_2` (`tag`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_u`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `posts`
--
ALTER TABLE `posts`
  MODIFY `id_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pro tabulku `tags`
--
ALTER TABLE `tags`
  MODIFY `id_t` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id_u` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_owner`) REFERENCES `users` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `posts_likes`
--
ALTER TABLE `posts_likes`
  ADD CONSTRAINT `posts_likes_ibfk_1` FOREIGN KEY (`post`) REFERENCES `posts` (`id_p`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_likes_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `tag_post`
--
ALTER TABLE `tag_post`
  ADD CONSTRAINT `tag_post_ibfk_1` FOREIGN KEY (`post`) REFERENCES `posts` (`id_p`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_post_ibfk_2` FOREIGN KEY (`tag`) REFERENCES `tags` (`id_t`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
