-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:8889
-- Tid vid skapande: 08 maj 2023 kl 14:35
-- Serverversion: 5.7.39
-- PHP-version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `cms`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `creator`
--

CREATE TABLE `creator` (
  `id` char(36) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `username` varchar(36) NOT NULL,
  `password` text NOT NULL,
  `biography` varchar(240) DEFAULT NULL,
  `profile_picture` char(36) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `creator`
--

INSERT INTO `creator` (`id`, `firstname`, `lastname`, `username`, `password`, `biography`, `profile_picture`, `created_at`) VALUES
('3fdd79ef291b37451a31b2e9c1ed419abe2c', 'Test', 'Testson', 'testuser', '$2y$10$nEgc7pk5V10/HOF4RbGNSevCHaPtnr0R/HEUKQETNFbfM1lQekmpW', NULL, 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-26 19:11:12'),
('5dbc8190c242bfb7a77483accf7e124a2e0f', 'New', 'Table', 'newtable', '$2y$10$YB/A5Ij1iGmSFfsXwMeDAOgFnPzgoITbghmGY3B5vWQmnv979QcuC', NULL, '2140e57a0c2271d8db780f774255dba050ec', '2023-04-27 19:26:12'),
('e322b544f6b8234b67a2f5282ede6528a214', 'Adam', 'Gustafsson', 'adam', '$2y$10$QFHCC5/44fwWx1mGXJ5f9ufegdxtJaRxHBahklrZyMYkzSsrRjq8G', 'Exercitation nisi qui commodo velit sint officia mollit exercitation exercitation qui. Consequat aliqua ex enim cillum fugiat ad tempor culpa reprehenderit do do ipsum laborum veniam deserunt. Irure deserunt consequat.', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-26 21:12:10'),
('f15e0a5cc06c1bd7088279605fef4a851a70', 'John ', 'Doe', 'johndoe', '$2y$10$AyeFHSqHybf/sA6lH0WE/eATq1sLfPtV/tKySAi/XGqYJOFv/0nRq', NULL, 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-27 13:55:42'),
('f9140151d45829f6be54598cea89c2239f5b', 'Adam', 'Gustafsson', 'adam1', '$2y$10$ohnGaROMSwi33GUcW1pw3O75iFyUUZz9hOYGgFeC/Y2XKMa.W8bHu', NULL, 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-26 21:21:22'),
('faf3ee3ddf7f201fd6f141db38b7bafd59b6', 'Test', 'Testsson', 'adminuser', '$2y$10$7gG.56iWIo8P.8Ncf7RnMOBFRIwph5iW7RRiBcRFe5cEBznTQ.tW6', NULL, 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-24 21:57:17');

-- --------------------------------------------------------

--
-- Tabellstruktur `image`
--

CREATE TABLE `image` (
  `id` char(36) NOT NULL,
  `filename` text NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `image`
--

INSERT INTO `image` (`id`, `filename`, `description`, `created_at`) VALUES
('092a5acd241cd770fcb0f3e6f6439c53b208', '644d3dc086d73.jpeg', '', '2023-04-29 17:54:40'),
('12f5181517f4e2ee9e3036df59a150590807', '644ea0ddef4d5.jpeg', '', '2023-04-30 19:09:49'),
('1a09a333b5e343c71040a0da35476f98e866', '644fc4f4a770d.jpg', '', '2023-05-01 15:56:04'),
('2140e57a0c2271d8db780f774255dba050ec', '644ab034b20da.jpg', '', '2023-04-27 19:26:12'),
('22860476028535e352885df141a5a1eaa6f9', '644aaf9c0f550.jpg', '', '2023-04-27 19:23:40'),
('272e6f2cfd26ac8a929bffaa7cf1ad24058a', '644fc5b26485c.jpeg', '', '2023-05-01 15:59:14'),
('28166f399f58266ca8f3a63301e54daa7af5', '644ec0d60db43.jpg', '', '2023-04-30 21:26:14'),
('29b7390c4adc77076447377fdef754af4102', '644b7f7f7cd0b.jpg', '', '2023-04-28 10:10:39'),
('33dacc33568c2b21697de6ec92fa98823c4a', '644b7ed6c7f35.jpg', '', '2023-04-28 10:07:50'),
('392fc4d397627d46fb74596753e60eb7bd91', '64526d91bc49f.jpeg', '', '2023-05-03 16:20:01'),
('449fde32e0e34baa6e7b779e9b402ab0d6d7', '644ed6fd69712.jpg', '', '2023-04-30 23:00:45'),
('550c93d34a76ec2dab5a7be0948b0dff758b', '64539a1a21805.jpeg', '', '2023-05-04 13:42:18'),
('5f905df0606c678acd8a4b0709f5b6b237fb', '644b7fbe0ee85.jpg', '', '2023-04-28 10:11:42'),
('6514d5f63e885930e975e2991333b7901450', '64526d156fd6f.jpeg', '', '2023-05-03 16:17:57'),
('7e1ba23d405db372b048ea200b94bc11d729', '644bd84db442b.jpeg', '', '2023-05-01 13:02:17'),
('8b246a5a9ac638b4b57fa451961867daf90b', '644ed36775668.jpeg', '', '2023-04-30 22:45:27'),
('8dc237393e8b077fbcd2742c84ef5c3aebb2', '644bed7b95693.jpg', '', '2023-04-28 17:59:55'),
('917f1a758227c592bd732d0ab7579a5bdf49', '644ed3716a912.png', '', '2023-04-30 22:45:37'),
('95664ab72f130e97eab70ecb3cccde19cd18', '644b809a4beeb.jpg', '', '2023-04-28 10:15:22'),
('a03b3c3f82e59085ebe140bfb3f95d45e70a', '644b8044c2840.jpg', '', '2023-04-28 10:13:56'),
('a877d6210a92ab1f40728cbb6265c75b7599', '6453ad11242d4.jpeg', 'Sjö. Två träd i skuggan i förgrunden. Stor sten i vattnet. Träd i bakgrunden. Guldigt solljus.', '2023-05-04 15:03:13'),
('ae341c96770a2b26819f03ef2604668e0e93', '644aab7bb5f61.jpg', 'Användares profilbild.', '2023-04-27 19:06:03'),
('b531fa0cb5de320796ff5ef8ad2e4c3ca5b5', '644f9cb64e261.png', '', '2023-05-01 13:04:22'),
('b814ff1893dc0a17cefac16de806a51b35be', '6453aaa08e46c.png', 'Denna bildtexten är redigerade, eller mer exakt tillagd, i efterhand :))', '2023-05-04 14:52:48'),
('c7bc34503020294838fbbf109edb66c335e2', '644fc5d8eed6f.jpeg', '', '2023-05-01 15:59:52'),
('ca21c4df85239546f0d85cf4c491f7992459', '6450fbe82e3c9.jpeg', '', '2023-05-02 14:02:48'),
('cf437ffe46351ca72ef53f991b87343e790d', '645399d299e3a.jpeg', '', '2023-05-04 13:41:06'),
('dd2a373abe27e13a4d44a91ed7354b3de607', '644bd84db442b.jpeg', '', '2023-04-28 16:29:33'),
('eaffa489c24c8fceefef05cab00825c31961', '644ea1589cccd.jpeg', '', '2023-04-30 19:11:52'),
('ec3d6de3428b9b87b2fc66ef8b2f407604c3', '644aac7cc64ba.png', '', '2023-04-27 19:10:20'),
('fd56708f057ea726594c0dd7e0da139a0b7f', '644b80fab6912.jpg', '', '2023-04-28 10:16:58');

-- --------------------------------------------------------

--
-- Tabellstruktur `post`
--

CREATE TABLE `post` (
  `id` varchar(36) CHARACTER SET utf8 NOT NULL,
  `author` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` char(36) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumpning av Data i tabell `post`
--

INSERT INTO `post` (`id`, `author`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
('44104303fa4e2c3d1e8d042eaf77746bad09', 'faf3ee3ddf7f201fd6f141db38b7bafd59b6', 'Tempor minim amet officia mollit officia aliquip nisi ipsum laborum.', 'Commodo non sint duis eiusmod ut mollit qui Lorem. Labore ipsum laborum adipisicing officia enim commodo nostrud exercitation nostrud qui. Occaecat elit dolore pariatur sint quis mollit quis cupidatat in ex ut non laboris. Fugiat veniam ad qui voluptate irure nostrud. Amet cillum voluptate adipisicing reprehenderit mollit sint culpa duis voluptate. Elit nostrud dolor magna proident et voluptate dolore ea pariatur occaecat commodo laborum ullamco consectetur id. Adipisicing minim nisi proident velit officia dolor. Proident commodo sunt esse exercitation enim eiusmod commodo quis velit mollit officia ad dolor cupidatat.\n\nElit commodo qui veniam deserunt pariatur mollit duis voluptate incididunt. In est excepteur sit est aliqua mollit culpa culpa cupidatat reprehenderit duis non labore. Cillum pariatur velit aute laborum esse minim elit anim incididunt cillum. Est est sunt cupidatat ea.\n\nNisi in sint laborum. Quis fugiat consequat do. Fugiat deserunt deserunt non excepteur consequat. Cupidatat aute ad cillum sint anim do incididunt laboris elit proident sit duis. Laboris exercitation fugiat nulla nostrud ea qui enim labore velit cillum dolore. Consectetur sunt aliqua deserunt pariatur culpa laboris enim consequat in cillum nulla esse veniam et cupidatat.\n\nAmet non laborum commodo enim anim deserunt irure irure elit magna aute deserunt nostrud. Laboris ullamco laboris adipisicing eu fugiat magna proident labore excepteur non ea. Commodo minim sint qui magna nulla commodo adipisicing nulla eu elit. Ea magna id elit minim. Non adipisicing mollit elit excepteur qui anim esse incididunt adipisicing laboris minim culpa esse. Cillum deserunt qui adipisicing sit aliquip do eu aliquip mollit occaecat irure laboris occaecat.\n\n// Adminuser', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-27 15:47:27', '2023-05-01 13:06:20'),
('4f8264cdb078e7ffb24f56ebf474d612e0ac', 'e322b544f6b8234b67a2f5282ede6528a214', 'Fungerar bildtexter nu då? :\'(', 'Bildtexter, hallå???', 'a877d6210a92ab1f40728cbb6265c75b7599', '2023-05-04 15:03:13', '2023-05-04 15:03:13'),
('63dcb5d9f0d57751cb828a55bb8fdd1f6563', 'faf3ee3ddf7f201fd6f141db38b7bafd59b6', 'Exibit – Ett enklare sätt att förstå juridiska dokument', 'Eu enim excepteur magna non culpa et. Dolore ex est non ullamco eu velit. Quis nulla voluptate anim eiusmod sint. Culpa voluptate nisi eiusmod in deserunt consectetur id incididunt esse velit pariatur aliqua. Dolore exercitation nisi dolor sit elit do nisi quis culpa amet proident. Labore magna irure consequat nisi fugiat laborum Lorem aute adipisicing id. Nisi esse enim cupidatat dolore in incididunt Lorem culpa non minim laborum non eu aliquip. Voluptate non eiusmod aliqua ea culpa ea ea excepteur culpa commodo non in anim.\r\n\r\nOfficia mollit aliqua sint qui adipisicing amet ex esse. Sit proident ex proident eiusmod laborum pariatur nulla mollit non magna id. Proident et ad laboris aute culpa. Esse ut sunt tempor exercitation amet mollit mollit id exercitation. Veniam mollit qui ad adipisicing reprehenderit ad officia. Dolor amet et officia est Lorem est. Minim eu duis in ut deserunt sint non irure labore consequat velit.\r\n\r\nIrure Lorem elit incididunt ipsum. Et esse reprehenderit culpa laboris ipsum commodo pariatur tempor minim sunt anim aliquip aliqua deserunt. Id do ut sunt. Lorem deserunt adipisicing aliqua. Occaecat aute laborum proident voluptate tempor magna mollit officia consectetur laboris ea sunt ex amet ullamco. Eu aliqua adipisicing nisi nisi sit laborum cillum non anim mollit laborum excepteur.\r\n\r\nConsectetur cupidatat ad nulla labore ad. Exercitation proident velit consectetur commodo elit Lorem irure proident officia commodo voluptate reprehenderit tempor ipsum. Officia laborum fugiat amet culpa enim ipsum ad ea amet laborum magna. Irure ipsum elit occaecat est mollit fugiat sint voluptate et labore. Et elit id voluptate esse pariatur voluptate adipisicing magna ut ut elit ad esse consectetur ullamco.\r\n\r\nProident est nulla velit cupidatat adipisicing tempor nisi commodo veniam.\r\n\r\nProident consectetur excepteur esse quis id magna. Minim sit ipsum aute commodo in dolore in deserunt enim consectetur fugiat voluptate dolor Lorem Lorem. Culpa veniam magna sit dolore sit incididunt esse ullamco. Nulla est cupidatat cillum qui sunt laborum consectetur eiusmod magna nisi in sunt cillum incididunt ex. Esse ullamco officia ullamco duis amet cupidatat eiusmod aliqua. Minim elit culpa non adipisicing anim veniam amet mollit minim.\r\n\r\nEx amet officia tempor cillum ut nisi. Aliquip non reprehenderit ipsum duis laboris occaecat et amet laborum nulla dolore. Occaecat deserunt mollit mollit in adipisicing magna est ea elit et elit. Quis adipisicing labore consectetur nulla eu. Officia dolore veniam adipisicing. Nostrud minim fugiat voluptate officia duis aliquip ea fugiat voluptate exercitation consequat ea mollit labore. Ullamco ipsum cupidatat in officia nostrud nisi officia tempor magna qui reprehenderit.\r\n\r\nDolor commodo consequat mollit elit laboris.', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-25 13:53:42', '2023-05-01 13:06:20'),
('767d44d73320448106589af588bb8b4ebdb9', 'e322b544f6b8234b67a2f5282ede6528a214', 'Emojis???', '🤯😶‍🌫️🫠', 'fd56708f057ea726594c0dd7e0da139a0b7f', '2023-04-28 10:16:58', '2023-05-01 13:06:20'),
('87c906b399608b971b9193abef0b6ff44034', 'e322b544f6b8234b67a2f5282ede6528a214', 'Meddelanden vid inlägg, yay!', 'Coolt :))', '092a5acd241cd770fcb0f3e6f6439c53b208', '2023-04-29 17:54:40', '2023-05-01 13:06:20'),
('8fdb060110678b5226859dcf4df461de1e2c', 'e322b544f6b8234b67a2f5282ede6528a214', 'Vilka tecken fungerar?', '¨¨¨¨\'¨\'\'\'\'\'\'\"!\"#€%&/()=?`©@£$∞§|[]≈±´¡”¥¢‰¶\\{}≠¿;:;<><\'¨^*~™™', '550c93d34a76ec2dab5a7be0948b0dff758b', '2023-05-02 14:02:48', '2023-05-02 14:02:48'),
('92d5f87d421393fba0242c142e9eda8a1013', 'e322b544f6b8234b67a2f5282ede6528a214', 'Uppdaterad?', 'Dolore nulla laborum id ullamco et occaecat. Eu dolor in ipsum. Adipisicing sint eiusmod velit commodo ullamco Lorem exercitation adipisicing ipsum veniam deserunt ut culpa proident deserunt. In dolor irure nostrud nisi mollit.\r\n\r\nDolore nulla laborum id ullamco et occaecat. Eu dolor in ipsum. Adipisicing sint eiusmod velit commodo ullamco Lorem exercitation adipisicing ipsum veniam deserunt ut culpa proident deserunt. In dolor irure nostrud nisi mollit.\r\n\r\nDolore nulla laborum id ullamco et occaecat. Eu dolor in ipsum. Adipisicing sint eiusmod velit commodo ullamco Lorem exercitation adipisicing ipsum veniam deserunt ut culpa proident deserunt. In dolor irure nostrud nisi mollit.', 'b531fa0cb5de320796ff5ef8ad2e4c3ca5b5', '2023-04-28 16:29:33', '2023-05-01 13:06:20'),
('9eb4819b98bcecd231156fdd92d862204853', 'e322b544f6b8234b67a2f5282ede6528a214', 'Bildbeskrivningar???', 'Tillgänglighet???', 'b814ff1893dc0a17cefac16de806a51b35be', '2023-05-04 14:52:48', '2023-05-04 14:52:48'),
('a67640609f68fefa3ff48cebb93e460acc9e', 'e322b544f6b8234b67a2f5282ede6528a214', 'Reaktioner, yay!!!', 'Reaktioner fungerar 🥳 Tror jag 😬\r\n\r\n// Adam', '8dc237393e8b077fbcd2742c84ef5c3aebb2', '2023-04-28 17:59:55', '2023-05-01 13:06:20'),
('aa6c48dc1b9be6248fe906a22992059ae064', 'faf3ee3ddf7f201fd6f141db38b7bafd59b6', 'Boro – En smidigare vardag', 'Esse aliqua officia exercitation irure duis anim esse. Laboris proident anim proident irure proident proident pariatur sint aliquip aute excepteur id commodo. Nulla adipisicing reprehenderit aliquip dolore ullamco tempor sit est fugiat eiusmod do adipisicing proident aute voluptate. Id est voluptate duis sit. Cillum fugiat in cupidatat aliquip exercitation sit laboris minim quis. Cillum ea dolor ad est ullamco aliquip fugiat occaecat elit occaecat.\r\n\r\nExcepteur ad duis aliquip officia et laborum et do deserunt sit commodo non in eu. Irure ea nostrud qui nisi aute id incididunt mollit elit sint est. Nisi aliquip tempor cupidatat duis magna cupidatat do elit velit veniam excepteur magna esse enim sunt. Aliqua non cupidatat duis.\r\n\r\nLaborum aute ut elit laboris consequat veniam duis duis esse sunt nulla aliqua tempor. Laboris exercitation esse cupidatat ipsum culpa nisi voluptate. Quis aute non pariatur ea mollit sint sunt laborum sint cillum. Laborum laborum proident eiusmod laboris minim cillum nisi aliquip fugiat quis. Et eu ut eiusmod voluptate quis veniam irure enim culpa aliqua magna. Aliquip aliqua exercitation est amet ullamco esse cupidatat amet irure reprehenderit sint aute. Exercitation elit aliquip elit ea irure exercitation amet nulla duis eu nostrud. Occaecat ad velit minim cillum non cillum mollit aliqua Lorem minim adipisicing aliquip irure laboris consequat.\r\n\r\n// Boro Team', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-26 18:33:12', '2023-05-01 13:06:20'),
('b22d183ba3c2075d8a2b20b5c62a18194fa5', 'e322b544f6b8234b67a2f5282ede6528a214', 'Testar nya editorn :))', 'Labore minim velit reprehenderit deserunt ut consectetur duis aute aliquip irure aliqua. Nostrud consequat do mollit ea nisi cillum eu Lorem. Duis pariatur eu veniam esse cupidatat. Minim ullamco excepteur non id voluptate dolor cupidatat minim consectetur commodo voluptate nisi nisi Lorem. Adipisicing ea enim veniam aliquip laborum veniam fugiat dolore pariatur do duis voluptate.', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-24 21:48:49', '2023-05-01 13:06:20'),
('d0e4aa8448380437915140634f00f594d113', 'e322b544f6b8234b67a2f5282ede6528a214', 'Förhandsvisning av bilder, jippi!', 'Officia magna laborum culpa occaecat. Nulla reprehenderit ad qui id consectetur fugiat voluptate nostrud exercitation tempor est excepteur reprehenderit veniam cupidatat. Aliqua id ex enim enim excepteur adipisicing sunt. Veniam nostrud enim ex in officia aliquip ipsum dolore laborum quis exercitation occaecat minim aliquip commodo.', 'ae341c96770a2b26819f03ef2604668e0e93', '2023-04-24 23:02:05', '2023-05-01 13:06:20'),
('deaec4eafe336be353b24cd54723d4a10414', 'faf3ee3ddf7f201fd6f141db38b7bafd59b6', 'Enim cillum labore in mollit aliqua labore.', 'Nisi proident deserunt et. Exercitation magna nulla eu eu cillum sunt cillum aute minim amet ipsum in adipisicing culpa. Aliqua et minim laboris fugiat sit pariatur culpa tempor. Exercitation tempor do fugiat elit excepteur pariatur irure anim eiusmod. Id magna veniam ipsum consectetur enim nulla voluptate cupidatat ex occaecat eiusmod tempor. Anim proident occaecat tempor esse minim cupidatat dolor aliqua sunt irure. Occaecat tempor irure irure cupidatat adipisicing in officia do.', 'ec3d6de3428b9b87b2fc66ef8b2f407604c3', '2023-04-27 19:10:20', '2023-05-01 13:06:20');

-- --------------------------------------------------------

--
-- Tabellstruktur `post_reaction`
--

CREATE TABLE `post_reaction` (
  `id` char(36) NOT NULL,
  `post` text NOT NULL,
  `type` set('mindblown','laughing','celebrating','thinking') NOT NULL,
  `reactor` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `post_reaction`
--

INSERT INTO `post_reaction` (`id`, `post`, `type`, `reactor`, `created_at`) VALUES
('0d8cc145f36ca468da6cfa17689211ccb678', '899d69175c3117dfbae006a23be9bf0d60d3', 'laughing', 'e322b544f6b8234b67a2f5282ede6528a214', '2023-05-01 22:30:55'),
('9de6b91fbf92eff9dcba678d3822ebc193df', '87c906b399608b971b9193abef0b6ff44034', 'celebrating', 'e322b544f6b8234b67a2f5282ede6528a214', '2023-05-01 20:26:44'),
('cf4cd5107685560366d5a59f1746ebcf508c', '4f8264cdb078e7ffb24f56ebf474d612e0ac', 'mindblown', 'e322b544f6b8234b67a2f5282ede6528a214', '2023-05-04 15:06:58'),
('f616e672675e6e3d6dbf29b4ac91874f76cd', '44104303fa4e2c3d1e8d042eaf77746bad09', 'celebrating', 'faf3ee3ddf7f201fd6f141db38b7bafd59b6', '2023-05-02 13:44:26');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `creator`
--
ALTER TABLE `creator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_creator_image` (`profile_picture`);

--
-- Index för tabell `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index för tabell `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_post_creator` (`author`),
  ADD KEY `FK_post_image` (`image`);

--
-- Index för tabell `post_reaction`
--
ALTER TABLE `post_reaction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `creator`
--
ALTER TABLE `creator`
  ADD CONSTRAINT `FK_creator_image` FOREIGN KEY (`profile_picture`) REFERENCES `image` (`id`);

--
-- Restriktioner för tabell `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_post_creator` FOREIGN KEY (`author`) REFERENCES `creator` (`id`),
  ADD CONSTRAINT `FK_post_image` FOREIGN KEY (`image`) REFERENCES `image` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
