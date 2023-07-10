-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 10, 2023 alle 20:32
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_comuni`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `em_province`
--

CREATE TABLE `em_province` (
  `codiceRegione` smallint(6) NOT NULL,
  `codiceProvincia` varchar(2) NOT NULL,
  `descrizioneProvincia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `em_province`
--

INSERT INTO `em_province` (`codiceRegione`, `codiceProvincia`, `descrizioneProvincia`) VALUES
(1, 'AQ', 'L\'Aquila'),
(1, 'CH', 'Chieti'),
(1, 'PE', 'Pescara'),
(1, 'TE', 'Teramo'),
(2, 'MT', 'Matera'),
(2, 'PZ', 'Potenza'),
(3, 'CS', 'Cosenza'),
(3, 'CZ', 'Catanzaro'),
(3, 'KR', 'Crotone'),
(3, 'RC', 'Reggio Calabria'),
(3, 'VV', 'Vibo Valentia'),
(4, 'AV', 'Avellino'),
(4, 'BN', 'Benevento'),
(4, 'CE', 'Caserta'),
(4, 'NA', 'Napoli'),
(4, 'SA', 'Salerno'),
(5, 'BO', 'Bologna'),
(5, 'FC', 'Forlì-Cesena'),
(5, 'FE', 'Ferrara'),
(5, 'MO', 'Modena'),
(5, 'PC', 'Piacenza'),
(5, 'PR', 'Parma'),
(5, 'RA', 'Ravenna'),
(5, 'RE', 'Reggio Emilia'),
(5, 'RN', 'Rimini'),
(6, 'GO', 'Gorizia'),
(6, 'PN', 'Pordenone'),
(6, 'TS', 'Trieste'),
(6, 'UD', 'Udine'),
(7, 'FR', 'Frosinone'),
(7, 'LT', 'Latina'),
(7, 'RI', 'Rieti'),
(7, 'RM', 'Roma'),
(7, 'VT', 'Viterbo'),
(8, 'GE', 'Genova'),
(8, 'IM', 'Imperia'),
(8, 'SP', 'La spezia'),
(8, 'SV', 'Savona'),
(9, 'BG', 'Bergamo'),
(9, 'BS', 'Brescia'),
(9, 'CO', 'Como'),
(9, 'CR', 'Cremona'),
(9, 'LC', 'Lecco'),
(9, 'LO', 'Lodi'),
(9, 'MB', 'Monza e della Brianza'),
(9, 'MI', 'Milano'),
(9, 'MN', 'Mantova'),
(9, 'PV', 'Pavia'),
(9, 'SO', 'Sondrio'),
(9, 'VA', 'Varese'),
(10, 'AN', 'Ancona'),
(10, 'AP', 'Ascoli Piceno'),
(10, 'FM', 'Fermo'),
(10, 'MC', 'Macerata'),
(10, 'PU', 'Pesaro e Urbino'),
(11, 'CB', 'Campobasso'),
(11, 'IS', 'Isernia'),
(12, 'AL', 'Alessandria'),
(12, 'AT', 'Asti'),
(12, 'BI', 'Biella'),
(12, 'CN', 'Cuneo'),
(12, 'NO', 'Novara'),
(12, 'TO', 'Torino'),
(12, 'VB', 'Verbano Cusio Ossola'),
(12, 'VC', 'Vercelli'),
(13, 'BA', 'Bari'),
(13, 'BR', 'Brindisi'),
(13, 'BT', 'Barletta-Andria-Trani'),
(13, 'FG', 'Foggia'),
(13, 'LE', 'Lecce'),
(13, 'TA', 'Taranto'),
(14, 'CA', 'Cagliari'),
(14, 'NU', 'Nuoro'),
(14, 'OR', 'Oristano'),
(14, 'SS', 'Sassari'),
(14, 'SU', 'Sud Sardegna'),
(15, 'AG', 'Agrigento'),
(15, 'CL', 'Caltanissetta'),
(15, 'CT', 'Catania'),
(15, 'EN', 'Enna'),
(15, 'ME', 'Messina'),
(15, 'PA', 'Palermo'),
(15, 'RG', 'Ragusa'),
(15, 'SR', 'Siracusa'),
(15, 'TP', 'Trapani'),
(16, 'AR', 'Arezzo'),
(16, 'FI', 'Firenze'),
(16, 'GR', 'Grosseto'),
(16, 'LI', 'Livorno'),
(16, 'LU', 'Lucca'),
(16, 'MS', 'Massa e Carrara'),
(16, 'PI', 'Pisa'),
(16, 'PO', 'Prato'),
(16, 'PT', 'Pistoia'),
(16, 'SI', 'Siena'),
(17, 'BZ', 'Bolzano'),
(17, 'TN', 'Trento'),
(18, 'PG', 'Perugia'),
(18, 'TR', 'Terni'),
(19, 'AO', 'Aosta'),
(20, 'BL', 'Belluno'),
(20, 'PD', 'Padova'),
(20, 'RO', 'Rovigo'),
(20, 'TV', 'Treviso'),
(20, 'VE', 'Venezia'),
(20, 'VI', 'Vicenza'),
(20, 'VR', 'Verona');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `em_province`
--
ALTER TABLE `em_province`
  ADD PRIMARY KEY (`codiceRegione`,`codiceProvincia`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
