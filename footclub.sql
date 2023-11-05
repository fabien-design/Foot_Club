-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 05 nov. 2023 à 22:10
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `footclub`
--

-- --------------------------------------------------------

--
-- Structure de la table `footmatch`
--

CREATE TABLE `footmatch` (
  `idMatch` int(11) NOT NULL,
  `teamScore` int(11) DEFAULT NULL,
  `opponentScore` int(11) DEFAULT NULL,
  `dateMatch` date NOT NULL,
  `teamId` int(11) NOT NULL,
  `opponentId` int(11) NOT NULL,
  `city` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- --------------------------------------------------------

--
-- Structure de la table `opposing_club`
--

CREATE TABLE `opposing_club` (
  `idOpponent` int(11) NOT NULL,
  `idOpponentTeam` int(11) NOT NULL,
  `addressOpponent` varchar(512) NOT NULL,
  `cityOpponent` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Structure de la table `player`
--

CREATE TABLE `player` (
  `idPlayer` int(11) NOT NULL,
  `namePlayer` varchar(256) NOT NULL,
  `lastnamePlayer` varchar(256) NOT NULL,
  `birthdatePlayer` date NOT NULL,
  `picturePlayer` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `player_has_team`
--

CREATE TABLE `player_has_team` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `role` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `staff_member`
--

CREATE TABLE `staff_member` (
  `idStaff` int(11) NOT NULL,
  `nameStaff` varchar(256) NOT NULL,
  `lastnameStaff` varchar(256) NOT NULL,
  `pictureStaff` varchar(512) NOT NULL,
  `roleStaff` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `team`
--

CREATE TABLE `team` (
  `idTeam` int(11) NOT NULL,
  `nameTeam` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `footmatch`
--
ALTER TABLE `footmatch`
  ADD PRIMARY KEY (`idMatch`),
  ADD KEY `team_fk1` (`teamId`),
  ADD KEY `opponent_fk2` (`opponentId`);

--
-- Index pour la table `opposing_club`
--
ALTER TABLE `opposing_club`
  ADD PRIMARY KEY (`idOpponent`),
  ADD KEY `fk_opponentTeam` (`idOpponentTeam`);

--
-- Index pour la table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`idPlayer`);

--
-- Index pour la table `player_has_team`
--
ALTER TABLE `player_has_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_fk1` (`player_id`),
  ADD KEY `team_fk2` (`team_id`);

--
-- Index pour la table `staff_member`
--
ALTER TABLE `staff_member`
  ADD PRIMARY KEY (`idStaff`);

--
-- Index pour la table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`idTeam`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `footmatch`
--
ALTER TABLE `footmatch`
  MODIFY `idMatch` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `opposing_club`
--
ALTER TABLE `opposing_club`
  MODIFY `idOpponent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `player`
--
ALTER TABLE `player`
  MODIFY `idPlayer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `player_has_team`
--
ALTER TABLE `player_has_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `staff_member`
--
ALTER TABLE `staff_member`
  MODIFY `idStaff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `team`
--
ALTER TABLE `team`
  MODIFY `idTeam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `footmatch`
--
ALTER TABLE `footmatch`
  ADD CONSTRAINT `opponent_fk2` FOREIGN KEY (`opponentId`) REFERENCES `opposing_club` (`idOpponent`),
  ADD CONSTRAINT `team_fk1` FOREIGN KEY (`teamId`) REFERENCES `team` (`idTeam`);

--
-- Contraintes pour la table `opposing_club`
--
ALTER TABLE `opposing_club`
  ADD CONSTRAINT `fk_opponentTeam` FOREIGN KEY (`idOpponentTeam`) REFERENCES `team` (`idTeam`);

--
-- Contraintes pour la table `player_has_team`
--
ALTER TABLE `player_has_team`
  ADD CONSTRAINT `player_fk1` FOREIGN KEY (`player_id`) REFERENCES `player` (`idPlayer`),
  ADD CONSTRAINT `team_fk2` FOREIGN KEY (`team_id`) REFERENCES `team` (`idTeam`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
