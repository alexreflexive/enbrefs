-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 24 Mai 2016 à 02:22
-- Version du serveur: 5.5.49-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `enbrefs`
--

-- --------------------------------------------------------

--
-- Structure de la table `enb_articles`
--

CREATE TABLE IF NOT EXISTS `enb_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `name` varchar(64) NOT NULL,
  `abstract` mediumtext NOT NULL,
  `text` mediumtext NOT NULL,
  `id_writer` int(11) NOT NULL,
  `published` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `id_previous` int(11) NOT NULL,
  `id_next` int(11) NOT NULL,
  `id_first` int(11) NOT NULL,
  `id_last` int(11) NOT NULL,
  `int_links` text NOT NULL,
  `ext_links` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `enb_articles`
--

INSERT INTO `enb_articles` (`id`, `title`, `name`, `abstract`, `text`, `id_writer`, `published`, `modified`, `id_status`, `id_parent`, `id_previous`, `id_next`, `id_first`, `id_last`, `int_links`, `ext_links`) VALUES
(1, 'Accueil', 'Accueil', '', 'Bienvenue à  ce modeste site consacré à  quelques domaines qui me sont plus ou moins familiers. \r\n\r\n', 2, '2015-07-16 00:35:51', '2016-05-24 00:17:18', 3, 0, 0, 2, 11, 13, '', ''),
(2, 'Plan', 'Plan', '', 'Lorem ipsum dolor sit amet,', 2, '2015-07-16 00:35:51', '2015-07-16 00:35:51', 3, 0, 1, 0, 0, 0, '', ''),
(11, 'Développement', 'Développement', '', 'Le développement, ou la programmation, c''est l''art d''écrire des instructions qui seront exécutées par l''ordinateur. ', 2, '2015-07-16 01:13:00', '2016-05-24 00:20:35', 3, 1, 0, 12, 0, 0, '', ''),
(12, 'Création web', 'Création web', 'Nouveau abstract', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sed pulvinar ipsum. Aenean id odio quam. Vestibulum eget aliquet diam. Curabitur placerat consequat urna quis.', 2, '2015-07-16 01:20:03', '2016-05-24 00:22:47', 0, 1, 11, 13, 0, 0, 'a:1:{i:0;s:2:"12";}', ''),
(13, 'Graphisme', 'Graphisme', 'Nouveau abstract', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sed pulvinar ipsum. Aenean id odio quam. Vestibulum eget aliquet diam. Curabitur placerat consequat urna quis.', 2, '2015-07-16 01:22:13', '2015-07-16 01:22:13', 0, 1, 12, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `enb_members`
--

CREATE TABLE IF NOT EXISTS `enb_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(64) CHARACTER SET latin1 NOT NULL,
  `password` varchar(128) CHARACTER SET latin1 NOT NULL,
  `email` varchar(64) CHARACTER SET latin1 NOT NULL,
  `id_privilege` int(11) NOT NULL,
  `devise` text CHARACTER SET latin1 NOT NULL,
  `language` varchar(2) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `enb_members`
--

INSERT INTO `enb_members` (`id`, `pseudo`, `password`, `email`, `id_privilege`, `devise`, `language`) VALUES
(1, 'initiative', '9f528266f6b762e6adacc1507f237c2ee0fa014cde00853bd65cee01fd2b558149266eeb4e4d90efe833db55be05488b3c73c4dd7b13b7307cac16ce3c6a9d7c', 'initiative@chezgodot.com', 5, '', 'en'),
(2, 'Rédacteur', '9f528266f6b762e6adacc1507f237c2ee0fa014cde00853bd65cee01fd2b558149266eeb4e4d90efe833db55be05488b3c73c4dd7b13b7307cac16ce3c6a9d7c', 'redacteur@chezgodot.com', 1, '', 'en');

-- --------------------------------------------------------

--
-- Structure de la table `enb_pm_inbox`
--

CREATE TABLE IF NOT EXISTS `enb_pm_inbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(124) CHARACTER SET latin1 NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `enb_pm_sentbox`
--

CREATE TABLE IF NOT EXISTS `enb_pm_sentbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(124) CHARACTER SET latin1 NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `enb_site_preferences`
--

CREATE TABLE IF NOT EXISTS `enb_site_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(124) CHARACTER SET latin1 NOT NULL,
  `devise` tinytext CHARACTER SET latin1 NOT NULL,
  `default_lang` varchar(2) CHARACTER SET latin1 NOT NULL,
  `rows_per_page` int(11) NOT NULL,
  `id_inscription` int(11) NOT NULL,
  `echo_log` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `enb_site_preferences`
--

INSERT INTO `enb_site_preferences` (`id`, `name`, `devise`, `default_lang`, `rows_per_page`, `id_inscription`, `echo_log`) VALUES
(1, 'EnBrefs', 'Pour un monde avec des verres Ã  moitiÃ© pleins.', 'fr', 5, 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
