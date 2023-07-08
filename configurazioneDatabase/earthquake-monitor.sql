-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 05, 2023 alle 07:46
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
-- Database: `earthquake-monitor`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `em_codici_backup`
--

CREATE TABLE `em_codici_backup` (
  `idUtente` int(10) NOT NULL,
  `codice` varchar(128) NOT NULL,
  `dataGenerazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataUtilizzo` datetime DEFAULT NULL,
  `dataEliminazione` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_dispositivi_fisici`
--

CREATE TABLE `em_dispositivi_fisici` (
  `idDispositivoFisico` varchar(128) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `nomeDispositivo` varchar(128) DEFAULT NULL,
  `dataAbilitazione` datetime DEFAULT current_timestamp(),
  `dataDisabilitazione` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_indirizzi_ip`
--

CREATE TABLE `em_indirizzi_ip` (
  `indirizzoIp` varchar(128) NOT NULL,
  `contatoreAlert` int(10) NOT NULL DEFAULT 0,
  `dataBlocco` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_log`
--

CREATE TABLE `em_log` (
  `idLog` int(11) NOT NULL,
  `dataEvento` datetime NOT NULL DEFAULT current_timestamp(),
  `logLevel` varchar(32) NOT NULL,
  `testo` varchar(1024) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `indirizzoIp` varchar(128) NOT NULL,
  `metodoHttp` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_login`
--

CREATE TABLE `em_login` (
  `idLogin` varchar(128) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `idTipoLogin` varchar(32) NOT NULL,
  `token` varchar(128) DEFAULT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `indirizzoIp` varchar(128) NOT NULL,
  `userAgent` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_log_chiamate`
--

CREATE TABLE `em_log_chiamate` (
  `idLogChiamata` int(10) NOT NULL,
  `dataEvento` datetime NOT NULL DEFAULT current_timestamp(),
  `endpoint` varchar(1024) NOT NULL,
  `indirizzoIp` varchar(128) NOT NULL,
  `nomeMetodo` varchar(128) NOT NULL,
  `token` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_log_telegram`
--

CREATE TABLE `em_log_telegram` (
  `idLogTelegram` int(10) NOT NULL,
  `idTelegram` varchar(128) NOT NULL,
  `dataEvento` datetime NOT NULL DEFAULT current_timestamp(),
  `jsonBody` varchar(4096) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_metodi_login`
--

CREATE TABLE `em_metodi_login` (
  `idTipoMetodoLogin` varchar(32) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `dataInizioValidita` datetime NOT NULL DEFAULT current_timestamp(),
  `dataFineValidita` datetime DEFAULT NULL,
  `isPredefinito` tinyint(1) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_metodi_rec_psw`
--

CREATE TABLE `em_metodi_rec_psw` (
  `idTipoMetodoRecPsw` varchar(32) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `dataInizioValidita` datetime NOT NULL DEFAULT current_timestamp(),
  `dataFineValidita` int(11) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_notifiche`
--

CREATE TABLE `em_notifiche` (
  `idNotifica` int(10) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `dataInvio` datetime NOT NULL DEFAULT current_timestamp(),
  `dataLettura` datetime DEFAULT NULL,
  `dataEliminazione` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_notifiche_telegram`
--

CREATE TABLE `em_notifiche_telegram` (
  `idNotificaTelegram` int(10) NOT NULL,
  `idTelegram` varchar(128) NOT NULL,
  `dataInvio` datetime NOT NULL DEFAULT current_timestamp(),
  `testo` varchar(4096) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_qr_code`
--

CREATE TABLE `em_qr_code` (
  `idQrCode` varchar(128) NOT NULL,
  `dataInizioValidita` datetime NOT NULL DEFAULT current_timestamp(),
  `dataFineValidita` datetime DEFAULT NULL,
  `idUtente` int(10) DEFAULT NULL,
  `indirizzoIp` varchar(128) NOT NULL,
  `userAgent` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_rec_psw`
--

CREATE TABLE `em_rec_psw` (
  `idRecPsw` varchar(128) NOT NULL,
  `idTipoRecPsw` varchar(128) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `indirizzoIp` varchar(128) NOT NULL,
  `userAgent` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_risorse`
--

CREATE TABLE `em_risorse` (
  `idRisorsa` varchar(128) NOT NULL,
  `nomeMetodo` varchar(128) NOT NULL,
  `descrizione` varchar(1024) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataEliminazione` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_ruoli_risorse`
--

CREATE TABLE `em_ruoli_risorse` (
  `idTipoRuolo` varchar(32) NOT NULL,
  `idRisorsa` varchar(128) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_ruoli_utenti`
--

CREATE TABLE `em_ruoli_utenti` (
  `idTipoRuolo` varchar(32) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_ruoli_voci_menu`
--

CREATE TABLE `em_ruoli_voci_menu` (
  `idTipoRuolo` varchar(32) NOT NULL,
  `idVoceMenu` int(10) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_telegram`
--

CREATE TABLE `em_telegram` (
  `idTelegram` varchar(128) NOT NULL,
  `idUtente` int(10) DEFAULT NULL,
  `nazione` varchar(128) DEFAULT NULL,
  `usernameTelegram` varchar(128) DEFAULT NULL,
  `dataAbilitazione` datetime DEFAULT NULL,
  `dataDisabilitazione` datetime DEFAULT NULL,
  `dataBlocco` datetime DEFAULT NULL,
  `contatoreAlert` int(10) NOT NULL DEFAULT 0,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `codiceAssociazione` varchar(128) NOT NULL,
  `idDispositivoFisico` varchar(128) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_token`
--

CREATE TABLE `em_token` (
  `token` varchar(128) NOT NULL,
  `idLogin` varchar(128) NOT NULL,
  `idUtente` int(10) NOT NULL,
  `dataGenerazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataInizioValidita` datetime DEFAULT NULL,
  `dataFineValidita` datetime DEFAULT NULL,
  `indirizzoIp` varchar(128) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `dataUltimoUtilizzo` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_two_fact`
--

CREATE TABLE `em_two_fact` (
  `idTwoFact` varchar(128) NOT NULL,
  `idLogin` varchar(128) NOT NULL,
  `idRecPsw` varchar(128) NOT NULL,
  `codice` varchar(32) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataUtilizzo` datetime DEFAULT NULL,
  `tentativi` int(11) NOT NULL,
  `indirizzoIp` varchar(128) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_t_metodi_login`
--

CREATE TABLE `em_t_metodi_login` (
  `idTipoMetodoLogin` varchar(32) NOT NULL,
  `descrizione` varchar(1024) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_t_metodi_rec_psw`
--

CREATE TABLE `em_t_metodi_rec_psw` (
  `idTipoMetodoRecPsw` varchar(32) NOT NULL,
  `descrizione` varchar(1024) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_t_notifiche`
--

CREATE TABLE `em_t_notifiche` (
  `idNotifica` int(10) NOT NULL,
  `titolo` varchar(1024) NOT NULL,
  `testo` varchar(1024) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataEliminazione` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_t_ruoli`
--

CREATE TABLE `em_t_ruoli` (
  `idTipoRuolo` varchar(32) NOT NULL,
  `descrizione` varchar(1024) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_utenti`
--

CREATE TABLE `em_utenti` (
  `idUtente` int(10) NOT NULL,
  `nome` varchar(1024) NOT NULL,
  `cognome` varchar(1024) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `dataCreazione` datetime NOT NULL DEFAULT current_timestamp(),
  `dataUltimaModifica` datetime DEFAULT NULL,
  `dataEliminazione` datetime DEFAULT NULL,
  `dataBlocco` datetime DEFAULT NULL,
  `tentativiCodiciBackup` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `em_voci_menu`
--

CREATE TABLE `em_voci_menu` (
  `idVoceMenu` int(10) NOT NULL,
  `idVoceMenuPadre` int(10) DEFAULT NULL,
  `descrizione` varchar(1024) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `icona` varchar(1024) NOT NULL,
  `ordine` int(10) NOT NULL DEFAULT 1,
  `visibile` tinyint(4) NOT NULL,
  `dataCreazione` datetime NOT NULL,
  `dataEliminazione` datetime DEFAULT NULL
) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `em_codici_backup`
--
ALTER TABLE `em_codici_backup`
  ADD PRIMARY KEY (`idUtente`,`codice`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `em_dispositivi_fisici`
--
ALTER TABLE `em_dispositivi_fisici`
  ADD PRIMARY KEY (`idDispositivoFisico`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `em_indirizzi_ip`
--
ALTER TABLE `em_indirizzi_ip`
  ADD PRIMARY KEY (`indirizzoIp`);

--
-- Indici per le tabelle `em_log`
--
ALTER TABLE `em_log`
  ADD PRIMARY KEY (`idLog`),
  ADD KEY `indirizzoIp` (`indirizzoIp`);

--
-- Indici per le tabelle `em_login`
--
ALTER TABLE `em_login`
  ADD PRIMARY KEY (`idLogin`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idUtente_2` (`idUtente`,`idTipoLogin`);

--
-- Indici per le tabelle `em_log_chiamate`
--
ALTER TABLE `em_log_chiamate`
  ADD PRIMARY KEY (`idLogChiamata`),
  ADD KEY `indirizzoIp` (`indirizzoIp`,`token`);

--
-- Indici per le tabelle `em_log_telegram`
--
ALTER TABLE `em_log_telegram`
  ADD PRIMARY KEY (`idLogTelegram`);

--
-- Indici per le tabelle `em_metodi_login`
--
ALTER TABLE `em_metodi_login`
  ADD PRIMARY KEY (`idTipoMetodoLogin`,`idUtente`,`dataInizioValidita`),
  ADD KEY `idTipoMetodoLogin` (`idTipoMetodoLogin`,`idUtente`);

--
-- Indici per le tabelle `em_metodi_rec_psw`
--
ALTER TABLE `em_metodi_rec_psw`
  ADD PRIMARY KEY (`idTipoMetodoRecPsw`,`idUtente`,`dataInizioValidita`),
  ADD KEY `idTipoMetodoRecPsw` (`idTipoMetodoRecPsw`,`idUtente`);

--
-- Indici per le tabelle `em_notifiche`
--
ALTER TABLE `em_notifiche`
  ADD PRIMARY KEY (`idNotifica`,`idUtente`),
  ADD KEY `idNotifica` (`idNotifica`,`idUtente`);

--
-- Indici per le tabelle `em_notifiche_telegram`
--
ALTER TABLE `em_notifiche_telegram`
  ADD PRIMARY KEY (`idNotificaTelegram`);

--
-- Indici per le tabelle `em_qr_code`
--
ALTER TABLE `em_qr_code`
  ADD PRIMARY KEY (`idQrCode`),
  ADD KEY `idUtente` (`idUtente`,`indirizzoIp`);

--
-- Indici per le tabelle `em_rec_psw`
--
ALTER TABLE `em_rec_psw`
  ADD PRIMARY KEY (`idRecPsw`),
  ADD KEY `idTipoRecPsw` (`idTipoRecPsw`,`idUtente`);

--
-- Indici per le tabelle `em_risorse`
--
ALTER TABLE `em_risorse`
  ADD PRIMARY KEY (`idRisorsa`);

--
-- Indici per le tabelle `em_ruoli_risorse`
--
ALTER TABLE `em_ruoli_risorse`
  ADD PRIMARY KEY (`idTipoRuolo`,`idRisorsa`),
  ADD KEY `idTipoRuolo` (`idTipoRuolo`,`idRisorsa`),
  ADD KEY `idRisorsa` (`idRisorsa`);

--
-- Indici per le tabelle `em_ruoli_utenti`
--
ALTER TABLE `em_ruoli_utenti`
  ADD PRIMARY KEY (`idTipoRuolo`,`idUtente`),
  ADD KEY `idTipoRuolo` (`idTipoRuolo`,`idUtente`);

--
-- Indici per le tabelle `em_ruoli_voci_menu`
--
ALTER TABLE `em_ruoli_voci_menu`
  ADD PRIMARY KEY (`idTipoRuolo`,`idVoceMenu`),
  ADD KEY `idTipoRuolo` (`idTipoRuolo`,`idVoceMenu`);

--
-- Indici per le tabelle `em_telegram`
--
ALTER TABLE `em_telegram`
  ADD PRIMARY KEY (`idTelegram`);

--
-- Indici per le tabelle `em_token`
--
ALTER TABLE `em_token`
  ADD PRIMARY KEY (`token`),
  ADD KEY `idLogin` (`idLogin`,`idUtente`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `em_two_fact`
--
ALTER TABLE `em_two_fact`
  ADD PRIMARY KEY (`idTwoFact`),
  ADD KEY `idLogin` (`idLogin`),
  ADD KEY `idRecPsw` (`idRecPsw`);

--
-- Indici per le tabelle `em_t_metodi_login`
--
ALTER TABLE `em_t_metodi_login`
  ADD PRIMARY KEY (`idTipoMetodoLogin`);

--
-- Indici per le tabelle `em_t_metodi_rec_psw`
--
ALTER TABLE `em_t_metodi_rec_psw`
  ADD PRIMARY KEY (`idTipoMetodoRecPsw`);

--
-- Indici per le tabelle `em_t_notifiche`
--
ALTER TABLE `em_t_notifiche`
  ADD PRIMARY KEY (`idNotifica`);

--
-- Indici per le tabelle `em_t_ruoli`
--
ALTER TABLE `em_t_ruoli`
  ADD PRIMARY KEY (`idTipoRuolo`);

--
-- Indici per le tabelle `em_utenti`
--
ALTER TABLE `em_utenti`
  ADD PRIMARY KEY (`idUtente`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- Indici per le tabelle `em_voci_menu`
--
ALTER TABLE `em_voci_menu`
  ADD PRIMARY KEY (`idVoceMenu`),
  ADD KEY `idVoceMenuPadre` (`idVoceMenuPadre`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `em_log`
--
ALTER TABLE `em_log`
  MODIFY `idLog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_log_chiamate`
--
ALTER TABLE `em_log_chiamate`
  MODIFY `idLogChiamata` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_log_telegram`
--
ALTER TABLE `em_log_telegram`
  MODIFY `idLogTelegram` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_notifiche_telegram`
--
ALTER TABLE `em_notifiche_telegram`
  MODIFY `idNotificaTelegram` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_t_notifiche`
--
ALTER TABLE `em_t_notifiche`
  MODIFY `idNotifica` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_utenti`
--
ALTER TABLE `em_utenti`
  MODIFY `idUtente` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `em_voci_menu`
--
ALTER TABLE `em_voci_menu`
  MODIFY `idVoceMenu` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
