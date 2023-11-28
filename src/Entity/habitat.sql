-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 07 sep. 2023 à 12:27
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de données : `infinitytelecom`

-- Structure de la table `habitat`

CREATE TABLE `habitat` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `images` VARCHAR(255) DEFAULT NULL,
  `quantite` INT NOT NULL,
  `prix` INT NOT NULL,
  `type` TEXT NOT NULL,
  `famille` TEXT NOT NULL,
  `host_id` INT DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `IDX_3B37B2E81FB8D185` (`host_id`),
  CONSTRAINT `FK_3B37B2E81FB8D185` FOREIGN KEY (`host_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Déchargement des données de la table `habitat`

-- AUTO_INCREMENT pour la table `habitat`
ALTER TABLE `habitat`
  MODIFY `id` INT AUTO_INCREMENT NOT NULL, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
