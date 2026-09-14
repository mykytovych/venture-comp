-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Вер 14 2026 р., 23:29
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `venture_comp`
--

-- --------------------------------------------------------

--
-- Структура таблиці `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'В опрацюванні..',
  `payment_status` varchar(50) DEFAULT 'Неоплачений'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `tour_id`, `booking_date`, `status`, `payment_status`) VALUES
(1, 2, 7, '2024-06-27 14:21:04', 'В опрацюванні..', 'Неоплачений'),
(2, 2, 3, '2024-05-21 09:41:19', 'В опрацюванні..', 'Оплачено!'),
(3, 4, 7, '2024-06-27 19:18:44', 'В опрацюванні...', 'Неоплачений'),
(4, 2, 3, '2024-06-28 07:04:28', 'В опрацюванні...', 'Неоплачений'),
(5, 2, 4, '2024-06-28 07:04:28', 'В опрацюванні...', 'Неоплачений'),
(6, 2, 7, '2024-06-28 07:04:28', 'В опрацюванні...', 'Неоплачений'),
(7, 2, 3, '2024-06-28 11:34:10', 'В опрацюванні...', 'Неоплачений'),
(8, 2, 4, '2024-06-28 11:34:10', 'В опрацюванні...', 'Неоплачений'),
(9, 11, 3, '2025-04-15 12:15:18', 'В опрацюванні...', 'Неоплачений');

-- --------------------------------------------------------

--
-- Структура таблиці `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` decimal(11,0) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `reviews`
--

INSERT INTO `reviews` (`review_id`, `tour_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 4, 1, 5, 'Блабла', '2024-06-27 07:19:27'),
(3, 3, 1, 3, 'Не сподобалося', '2024-06-27 08:30:21'),
(4, 4, 1, 2, 'Нууу', '2024-06-27 08:30:40'),
(7, 4, 2, 1, 'іва', '2024-06-27 08:35:57'),
(8, 5, 4, 5, 'Чудовий зимовий тур!', '2024-06-27 08:38:01'),
(9, 3, 4, 3, 'Бувало й краще', '2024-06-27 08:43:40'),
(10, 4, 2, 5, 'Чудовий тур', '2024-06-28 11:33:11'),
(11, 8, 7, 5, 'ВІапроалпопаовіапв', '2025-03-30 18:30:22'),
(12, 8, 7, 3, 'вкпвапвп', '2025-03-30 18:30:26'),
(13, 8, 7, 1, 'ІВаАІВЧАІВА', '2025-03-30 18:30:33');

-- --------------------------------------------------------

--
-- Структура таблиці `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `tags`
--

INSERT INTO `tags` (`tag_id`, `name`, `icon_url`) VALUES
(1, 'Тарас', 'https://www.svgrepo.com/show/132905/analytic-report.svg'),
(2, 'Полювання за головами', 'https://www.svgrepo.com/show/15272/target-dummy.svg'),
(3, 'APHO', 'https://www.svgrepo.com/show/10041/goals.svg'),
(4, 'Тур на природі', 'https://www.svgrepo.com/show/132905/analytic-report.svg'),
(5, 'Гарячі джерела', 'https://www.svgrepo.com/show/15272/target-dummy.svg'),
(6, 'Пляж', 'https://www.svgrepo.com/show/488799/beach.svg'),
(7, 'Тур з тваринами', 'https://www.svgrepo.com/show/479104/paw-3.svg'),
(8, 'Сімейний тур', 'https://www.svgrepo.com/show/115078/family-silhouette.svg'),
(9, 'Безкоштовні сніданки', 'https://www.svgrepo.com/show/490738/food-restaurant.svg');

-- --------------------------------------------------------

--
-- Структура таблиці `tours`
--

CREATE TABLE `tours` (
  `tour_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `available_seats` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default_tour.png',
  `short_description` text NOT NULL,
  `detailed_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `tours`
--

INSERT INTO `tours` (`tour_id`, `title`, `class`, `country`, `city`, `available_seats`, `price`, `image`, `short_description`, `detailed_description`, `created_at`) VALUES
(3, 'Національний парк Гранд-Каньйон', 'B', 'США', 'штат Аризона', 120, 23590.00, 'gc_ar_usa.jpg', 'аціональний парк Гранд-Каньйон — один з найстаріших національних парків США, розташований у штаті Аризона.', 'На території парку розташований Великий каньйон річки Колорадо, одне з визнаних природних чудес світу. Площа парку — 4927 км². Південний край каньйону є найвідвідуванішим, тут же знаходяться популярні оглядові точки. Північний край каньйону набагато менш відвідуваний. Інші частини каньйону віддалені й важкодоступні, хоча багато з них є досяжними по пішохідних маршрутах і путівцях. Територія навколо Гранд-Каньйону стала національним пам\'ятником 11 січня 1908 і оголошена національним парком 19 лютого 1919. Створення парку стало одним з перших успіхів природоохоронного руху. Статус національного парку допоміг перешкодити виконанню планів з будівництва греблі на річці Колорадо всередині меж парку (можливо, що відсутність такого статусу у каньйону Глен і було причиною дозволу будівництва греблі вище за течією річки і його затоплення, після чого утворилося озеро Пауел). ЮНЕСКО оголосив парк об\'єктом Світової спадщини. Гранд-Каньйон, включаючи його велику систему суміжних каньйонів, не є ні найбільшим, ні найглибшим у світі, проте він цінується перш за все за своє гармонійне поєднання розміру, глибини і багатобарвних шарів оголених гірських порід, які датуються аж до докембрійського періоду.\r\n\r\n', '2024-06-26 18:39:25'),
(4, 'Відкрийте для себе величні Афіни!', 'S', 'Греція', 'Афіни', 19, 45600.00, 'athens2.jpg', 'На своєму узбережжі Атени розташували величезна кількість екзотичних бухт з чудовими пляжами на будь-який смак. Організовані і дикі, піщані і галькові, вони тягнуться на багато кілометрів. ', 'Відкрийте для себе величні Афіни, місто, де народилася демократія, філософія та мистецтво. Цей ексклюзивний тур S+ класу веде вас до глибини грецької історії та культури, знайомлячи з вражаючими архітектурними пам\'ятками, мальовничими пейзажами та смачною кухнею. Ви прогуляєтеся слідами грецьких богів, дослідите стародавні храми та театри, насолодитесь панорамними краєвидами з вершини Акрополя, і відчуєте справжню атмосферу цього дивовижного міста.', '2024-06-26 20:09:20'),
(5, 'gfh', 'B', 'fdg', 'df', 4353, 34.00, 'image (17).png', '345', '435', '2024-06-26 20:09:47'),
(6, 'Чарівна поїздка Карпатами', 'A', 'Україна', 'Буковель', 10, 1500.00, 'image (15).png', '1', '1234', '2024-06-27 10:52:59'),
(7, 'Villa Palasa', 'A', 'Хорватія', 'Паласа', 30, 23500.00, 'img4.jpg', 'Апартаменти Villa Palasa розташовані в Платі, в 9 км від Пляж Св. Якова, і надають такі зручності, як безкоштовна самостійне паркування і ресторан. Ці апартаменти розташовують окремим балконом додатково до міні-кухні.', 'Апартаменти Villa Palasa розташовані в Платі, в 9 км від Пляж Св. Якова, і надають такі зручності, як безкоштовна самостійне паркування і ресторан. Ці апартаменти розташовують окремим балконом додатково до міні-кухні.', '2024-06-27 14:34:50'),
(8, 'Магія Ісландії', 'S', 'Ісландія', 'Рейк\'явік', 15, 22500.00, 'image (3).png', 'Відчуйте магію Ісландії на цьому екскурсійному турі, де ви відвідаєте вулканічні пляжі, гейзери і засніжені піки.', 'Тур включає в себе екскурсії до визначних місць Ісландії, таких як Гейзер Гейсір, водоспад Гуллфос і національний парк Піннатангар. Ви також відвідаєте село Вік, де зможете спостерігати за китами. Проживання в комфортабельних готелях зі сніданками включено.', '2024-06-27 14:43:15');

-- --------------------------------------------------------

--
-- Структура таблиці `tour_tags`
--

CREATE TABLE `tour_tags` (
  `tour_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `tour_tags`
--

INSERT INTO `tour_tags` (`tour_id`, `tag_id`) VALUES
(3, 2),
(3, 4),
(3, 7),
(3, 8),
(4, 2),
(5, 2),
(5, 3),
(6, 1),
(8, 4),
(8, 5);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `profile_photo` varchar(255) DEFAULT 'default_pfp.png',
  `documents_archive` varchar(255) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`user_id`, `email`, `first_name`, `last_name`, `birthdate`, `profile_photo`, `documents_archive`, `password`, `is_admin`, `created_at`) VALUES
(1, 'a@q', 'Артур', 'Sc', '2024-06-02', 'default_pfp.png', NULL, 'q', 0, '2024-06-26 07:47:09'),
(2, 'carl@j', 'Carl', 'J', '2024-06-07', '763896bd9a716cf8ee91513c81edc82d.jpg', NULL, '$2y$10$SF4DHQjfAUDCXbxEIv.Kr.ZFTSTGOmxlLWAYksLE6/YQ6LbxL9oce', 1, '2024-06-26 07:49:53'),
(3, 'q@q', 'A', 'B', '2024-06-01', 'default_pfp.png', NULL, '$2y$10$8k4ZvKJwOKDTbjDhH6pXR.T0GFoah/FLsEfSI9T0fczB6548EZzjO', 0, '2024-06-26 14:57:44'),
(4, 'julya@ukr.net', 'Юлія', 'Кравчук', '1989-01-03', '763896bd9a716cf8ee91513c81edc82d.jpg', NULL, '$2y$10$oGwisHMvbTI7D8KgXZJiLOXN1Od9hoyN5oTbkQ8flYIjftsxSGqxe', 0, '2024-06-27 08:36:47'),
(5, 'artur_morgan@yahoo.com', 'Артур', 'Морган', '1863-06-22', 'rdr2-1280-1540463019272_160w.jpg', NULL, '$2y$10$i.arATeNN2UTDPD.U0JGxO11nQ5TdrLdel0ySus4Kj6veoqZE.6Uu', 0, '2024-06-28 06:38:14'),
(6, 'henry_wood@gmail.com', 'Генрі', 'Вуд', '1991-11-14', 'default_pfp.png', NULL, '$2y$10$QA4iCSEKFwxBp9b1FqdKieFDnRbO4QdKIglcIAmuwqzeNvyr1KPSK', 0, '2024-06-28 08:02:23'),
(7, 'gissa@gmail.com', 'Артур', 'Аравгвга', '2025-03-14', 'default_pfp.png', NULL, '$2y$10$Qr0LTLInblWxhrGqMF4cEe4eXi8ChbMbSNPPpYs4kLBGfZ1B/xNWu', 0, '2025-03-30 18:25:48'),
(8, 'ppp@ppp', 'And', 'Und', '2025-03-15', '4a1c4a9755e4d3bdfcb45a1c3a58712f.jpg', NULL, '$2y$10$oRx/IF5fN8IzrlAW/.Ak0u/X4vxCDKDcCGLCWONy74cRx/slu5YXS', 0, '2025-03-31 09:55:33'),
(9, 'ultimate.blood@blood.bmail', 'Ultimate', 'Blood', '6666-06-06', 'photo_2025-03-31_18-44-00.jpg', NULL, '$2y$10$NOJxrFo.PiL5MWU7dZn6QuRH/OqQKD1FzrGluKznn1yZjqhluKyni', 0, '2025-04-03 10:49:10'),
(10, 'brick@email.com', ' Ігор', 'Цегла', '1992-07-18', 'default_pfp.png', NULL, '$2y$10$vDbH5JYwLYE12j8kAy8jWuBPfJxcGthWUePd1SA9Pnf3/sIKxxzge', 0, '2025-04-15 10:24:35'),
(11, 'edki.work@gmail.com', 'edki.work@gmail.com', 'edki.work@gmail.com', '2025-04-04', 'default_pfp.png', NULL, '$2y$10$WZLL2Pg.vl9p1/AhrBDvw.BNVyEFsz45wp0SMKBRX5qWiUJYw07eu', 0, '2025-04-15 12:13:01'),
(12, '123@123', '123', '123', '2025-04-05', 'default_pfp.png', NULL, '$2y$10$r.id2PN7JP10.2sM7iSP3ed.AYFUF5zEc7uAic8480ZEugqJxLZmi', 0, '2025-04-22 10:00:27'),
(13, 'mp@mp', 'ad', 'ad', '2025-05-07', 'default_pfp.png', NULL, '$2y$10$mmzaWe4n9RGZmNv7nLwbz.vFv0NoH0W9UjrT.WfmReds8ZTLihYou', 0, '2025-09-03 20:24:52');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Індекси таблиці `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Індекси таблиці `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`tour_id`);

--
-- Індекси таблиці `tour_tags`
--
ALTER TABLE `tour_tags`
  ADD PRIMARY KEY (`tour_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблиці `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблиці `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблиці `tours`
--
ALTER TABLE `tours`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`);

--
-- Обмеження зовнішнього ключа таблиці `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Обмеження зовнішнього ключа таблиці `tour_tags`
--
ALTER TABLE `tour_tags`
  ADD CONSTRAINT `tour_tags_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`),
  ADD CONSTRAINT `tour_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
