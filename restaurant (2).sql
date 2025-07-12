-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 10. Jul 2025 um 10:23
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `restaurant`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellpositionen`
--

CREATE TABLE `bestellpositionen` (
  `id` int(11) NOT NULL,
  `bestellung_id` int(11) DEFAULT NULL,
  `speise_id` int(11) DEFAULT NULL,
  `menge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `bestellpositionen`
--

INSERT INTO `bestellpositionen` (`id`, `bestellung_id`, `speise_id`, `menge`) VALUES
(14, 11, 1, 1),
(17, 12, 2, 5),
(18, 13, 1, 1),
(20, 14, 3, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungen`
--

CREATE TABLE `bestellungen` (
  `id` int(11) NOT NULL,
  `tisch_nr` int(11) NOT NULL,
  `erstellt_am` datetime DEFAULT current_timestamp(),
  `status` enum('offen','in_bearbeitung','fertig') NOT NULL DEFAULT 'offen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `bestellungen`
--

INSERT INTO `bestellungen` (`id`, `tisch_nr`, `erstellt_am`, `status`) VALUES
(11, 2, '2025-07-10 10:06:02', 'offen'),
(12, 2, '2025-07-10 10:06:12', 'offen'),
(13, 22, '2025-07-10 10:11:20', 'offen'),
(14, 1, '2025-07-10 10:23:35', 'offen');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `speisekarte`
--

CREATE TABLE `speisekarte` (
  `id` int(11) NOT NULL,
  `gericht` varchar(100) NOT NULL,
  `preis` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `speisekarte`
--

INSERT INTO `speisekarte` (`id`, `gericht`, `preis`) VALUES
(1, 'Pizza Margherita', 8.50),
(2, 'Lasagne', 9.90),
(3, 'Spaghetti Bolognese', 8.90),
(4, 'Salat mit Hähnchen', 7.80);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwort` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `passwort`) VALUES
(1, 'testuser', '$2y$10$GsUWmUSNrjdxQISXVTzsaOeGAzoSyjWLfqTDl4rHjkXovjiZsB6z.');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bestellung_id` (`bestellung_id`),
  ADD KEY `speise_id` (`speise_id`);

--
-- Indizes für die Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `speisekarte`
--
ALTER TABLE `speisekarte`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `speisekarte`
--
ALTER TABLE `speisekarte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD CONSTRAINT `bestellpositionen_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellungen` (`id`),
  ADD CONSTRAINT `bestellpositionen_ibfk_2` FOREIGN KEY (`speise_id`) REFERENCES `speisekarte` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
