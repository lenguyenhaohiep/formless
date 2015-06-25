-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 25 Juin 2015 à 19:31
-- Version du serveur :  5.6.21
-- Version de PHP :  5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `authentication`
--

-- --------------------------------------------------------

--
-- Structure de la table `certificate`
--

CREATE TABLE IF NOT EXISTS `certificate` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `secret_key` longtext NOT NULL,
  `pubic_key` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `certificate`
--

INSERT INTO `certificate` (`id`, `user_id`, `secret_key`, `pubic_key`) VALUES
(3, 1, '-----BEGIN PGP PUBLIC KEY BLOCK-----\nComment: GPGTools - https://gpgtools.org\n\nmQINBFWGrkIBEAC/lerm6iDcx1VgxGL0BZ6BwTXOu06VybIGEDFFN9Gr7ySgLL5J\nLtP3mGagbmip+XZDxPshhnSIfwaD46rKaGs3ERK3w3coJZcOiQlDOQT1+UQOK3Ak\nZtjxE5g+VBRvIDFro1j7k+vgmLKtQ+M6HwSNVZGCXOoOdTBjwE3gnB2gaWvoVulp\nZ5SHBziQFU27x7oQgxCCkolRf4b4/z59UWnuKm52dlJfSUVyBqN+NIav2IUuTGLq\nRMAJ0xLs3dQbDUEMEt+3JtMxhd/KfJQfkhWQtgoSpihzl7cdgniMzUYwWFRSw8jw\ngRafCLIxqqpuHBFf9nhvKML4JnvMkjNPXIKaY21z0xdtXx/7KLZOtfTDhYUZA1To\nxsWFHX1xu9Bc+PBGbgfoEHPuG11S4D26gkeodgxGDmtsCy0nftBwHScIhCYohis4\naKTACW2Xw4o/uRO93LwO6D2sPu69o7I2xS85kCxrixtVYMFZtiiDNatEn4XbBxRh\nKCIakauigPY3tFplwJqlBvFGrwTcrgYnCAcA9uO41noRURf5y73+knmioe9n9i+/\nxKU+J8AOnaYgDI5jjrGoPdaS0dwGW/SH370KlvIaZyrsHCm40Xls0o/KkGEyBQpr\nYZW4Ly07NYKlUK73Bc19laSe3vb/YZMvMpTefHjX7MK5fkOAn7qGL63n8QARAQAB\niQIfBCABCgAJBQJVhrAYAh0AAAoJEH6xY4VejxykXEQP/2WTPQI+ltVHxRRxds6l\nQHeeD6lDb+RKC/Agjc2mxhh3q2H/SVr80YoMoolKhUJrz+PGkTcxYLhVF6HpaRFo\n0mdJ/kaaoX6JK8nbkxk7P7+zAAREe3+xrzGzddwevwlAwqPygrZ6OJWy48QGlaDY\nzS4uV8NmnSKyyJJgjVsSmzhN1KSb+ZDVYEEhdrJ/KQTT/YF76/jD+DKV1jkz94cE\naH0TV+8JSEvEE3T3k4cVkBBkggCpBzODXA7AbBWeKSKgJm59HPlOmppw1OyogEZ6\no7bChvTBg7txJ+uwO8RSs9YCFgKQlhpitUHsFZe5YrbBwuPuqr6ZWCht+Q/Fy3Ch\npkeoJ0KJ+zRmpI2zJqMJdXWFyl+gg5PPLaW+V2Q1YfVJAE8WDl+v3Bd5DrJDsmMP\nxuWSyZLlGDS9Ua2pSoy+nH9iCsjwsdRc52355XxpSK6LpxU+2OENTGBSCNm2XP6v\nVwJ+ar72Ed5XBgJAkokYqY7QJ/MfS8Sz7W8ddJkbPuZdPZ6TpSuiXVKflnNbgTIK\nLs6rucnGkYk/Kpphf8vFRiKZ1peJDu0akzPEH0doH1MeAkEbOo+M4xTk3UxYdTeL\nJSgrAjAROjGHzsJY2EcXtKOHtJbPbBGE6lm278ZTx5BcSuaCJmwj3KGAvx4JcqdC\nmdKL7wlL4Ic6tG/EPFvVFe+dtCNIaWVwIExlIDxsZW5ndXllbmhhb2hpZXBAZ21h\naWwuY29tPokCPQQTAQoAJwUCVYauQgIbAwUJB4YfgAULCQgHAwUVCgkICwUWAgMB\nAAIeAQIXgAAKCRB+sWOFXo8cpAMMD/9iQtScjoQZ1yzpjK0yTx1BFiSlQLopLGE0\n1XUGmqQ39e+a105XlimhWjPWI9vq6ZQcjNVZQ3lqGEbvDEe6BvIVitojb3v/GDe6\nipGuN+cBZ6n6wO9cvZ1GlQWLvtKv/skNfiWdXlh9e/AMHXKvKleTQ4oMSf14imgR\nroDI/b7OA2QOyRX1mYJyCo5m1MjciBT80Tn/t7M6j04L2UpE19zDVXKe0aJDNZUi\necgD5ljjUhgk25XHjr2z9FiqAvzUGEOwxHyLmkVQtO1HHuWDa/MFMfjhxTb/jsrT\nAMLQjRonIZ6n1vp2Yu8EJODsNzm09bH2tLL4hvedUe/ydtqxEVDSDkNcmz5LY76g\n5nECd7RL0dBX9MpzsxOtDFNFp5aMHP3uf8SE3ca0oPDg8Qpm0SECh6GZJuBNraNo\np4KOSsM3xCDBbsRA2vUhZQ2IOXYY4JbqBjrw3RyC0peUvKk0gccvajBClIGCXUmk\nhl5T/7SEqJTbSTJ3U9D8Iwre+q3HS6zV8PWy2VizHIUNaETHqmw92mfnGydlomro\nQqnQdNc9pahw0SkY4cJ1nMpeumraivLHlT5Zlz6nFnYdxmgS3GJo4OgnQincZgY9\nBzTQrMad70M4vRPthMJOEGz0yGpSDGKVzmreEyAKeK8n3i2Q6nbZwIQJpPjnjq2p\n4hC7+rSLlLkCDQRVhq5CARAAwziTlZMrItQ/B3eEkgq2b6S1T5HCKe6h/F67pOZD\nvMay5wWyXD+RIhDmNpOJK5tsg3Vm9yGrhYfbktufOkQlNnwCOBJQ3wsKINp/QwRD\ncxHS3Nf9mVESnkm2ZHkbO/UZmvel/EO7BDBTkOiQ8PERBbMk5LAxJVR34Q5IP3uz\nIhGEGpNs7kShw8GSj3XBKqvsqHQCiEaJ2AVn4abfdZmK9VflUMI7zTYr9Xkh0HTg\npvZn4cQrUuGNH+uZBHq/oYvhNT5yB56Hb8RCH7o76iSltaBr0MWHem0aRpTMp6Hn\nvAaQ8bglwT/6j2R2CKcQrqsEFX1OPT+1DzX2yt3Nz6wOht8voCYjIJsNDOQHpExc\nuESVKl8UDXsOhBXI1R00xKiL+v5ayo48A9xlMMR/0L/yqZVUKlfNbgVPmduSCmfM\nhakMPiGmlcfBBz2wIKPEFMe7h5iXlj3oGA4bySUD6Vt7eRzo4kwHquRixMvZNwqy\n9NeJTxjbuI87qw4sB3oTrfo8h5/mIkROkT6xw08CK3FKVuYX3XFliuK+yzfxi5O6\ncJ51waLhz5QstCgNs/vYpm55aC0gkEtdkbMDZT85nfMlPBDAYbRebDKV1IVEdliq\nBYZ1VepLuf4aXdHK2WbxCtWEVFTzHapNAjU/TfYyaFT//WTLAyoRpgTCxQVc5rnD\nN3EAEQEAAYkCJQQYAQoADwUCVYauQgIbDAUJB4YfgAAKCRB+sWOFXo8cpGgtD/0Y\ncn/eQ0VjJsLSRgv0++zEiA1aZYtunH7ANtxdCrCno4/H2Hjp0oWaJ1gG5eDOkDD6\n2jrzM8AWdn8A+WScKW31ktHIP2bhZVWTanXGVARPX3nYwYnzvgysVV9wli4DryM+\niC+WZ0fAIoYhRnlnhlLdPgWgDdTw+ki2srkj7k43SMdOjmm2XHTuvwjQ2EOXfDxK\ndgFVgWiOF1rc42YwmcnFVGtJJUCNpyWajcI7xNfD7B4ZlI6sP5ZNLySE9UOvhiVm\n+zoOEFyjvcVrZMFXNazkoNzVM3RE59QRCv9DkeIIFDRRgyEU6x40E+Oix+5Vhzir\ng6r2heTPM2WNdSlh+RjMklyKHQeMzO4dxSewJpSdA+hUTApTl04FchjiTn9lwdqv\npv2po28QYn/V/c/mTG9So03UekB0cvvRtR3JyVK7Rm5Iw8OJ9ssfxGjXCQTwqTPk\nSFtlERMn2yxOmmHe43DvRRU3Nf9I338S+OOoyGaSSASvtJn1DuAN0lIIn7JKH/vp\nSs9A344CtA64UNlzIIhRNEUZCTuRj6OAE/4tlQA2ZMlyuKMaqxC/IGSdRVE8tON2\nmM1HLpbDeJG4xr7nfPux3KWItREANz+NbA4Z0wFbSyvF9yd4VrcAQnxdIJf43car\nDP2TUI2UwG/xqypGtJaIf6J1zLnvD7V4aDWp4qNqHA==\n=rsEa\n-----END PGP PUBLIC KEY BLOCK-----\n-----BEGIN PGP PRIVATE KEY BLOCK-----\nComment: GPGTools - https://gpgtools.org\n\n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\n=TmeZ\n-----END PGP PRIVATE KEY BLOCK-----\n', '-----BEGIN PGP PUBLIC KEY BLOCK-----\nComment: GPGTools - https://gpgtools.org\n\n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\n=rsEa\n-----END PGP PUBLIC KEY BLOCK-----\n-----BEGIN PGP PRIVATE KEY BLOCK-----\nComment: GPGTools - https://gpgtools.org\n\n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\n=TmeZ\n-----END PGP PRIVATE KEY BLOCK-----\n');

-- --------------------------------------------------------

--
-- Structure de la table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `path_form` varchar(255) DEFAULT NULL,
  `data` longtext,
  `created_date` datetime NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `form`
--

INSERT INTO `form` (`id`, `user_id`, `type_id`, `title`, `path_form`, `data`, `created_date`, `status`) VALUES
(1, 1, 1, 'Hiep', NULL, '{"fields":[{"label":"Date","field_type":"date","required":true,"field_options":[],"cid":"c2","owner":"admin@admin.com","filled_by":"admin@admin.com","value":"07\\/03\\/1990"},{"label":"Signature","field_type":"sign","required":true,"field_options":[],"cid":"c6","owner":"admin@admin.com","filled_by":"admin@admin.com","value":["Nguyen Hao Hiep","Le",null]}],"info":{"type":{"id":1,"title":"type1"},"title":"Hiep","owner":{"firstname":"Admin","lastname":"istrator","email":"admin@admin.com"},"creation":{"date":"2015-06-25 16:45:33.000000","timezone_type":3,"timezone":"Europe\\/Berlin"}}}', '2015-06-25 16:45:33', 0);

-- --------------------------------------------------------

--
-- Structure de la table `form_relation`
--

CREATE TABLE IF NOT EXISTS `form_relation` (
`id` int(11) NOT NULL,
  `type_id_1` int(11) DEFAULT NULL,
  `type_id_2` int(11) DEFAULT NULL,
  `attr1` varchar(255) NOT NULL,
  `attr2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Structure de la table `group_type`
--

CREATE TABLE IF NOT EXISTS `group_type` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `group_type`
--

INSERT INTO `group_type` (`id`, `title`) VALUES
(1, 'group1');

-- --------------------------------------------------------

--
-- Structure de la table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
`id` int(11) unsigned NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `modify_history`
--

CREATE TABLE IF NOT EXISTS `modify_history` (
`id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `send_history`
--

CREATE TABLE IF NOT EXISTS `send_history` (
`id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `send_date` datetime NOT NULL,
  `message` longtext,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `share`
--

CREATE TABLE IF NOT EXISTS `share` (
`id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `attrs` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
`id` int(11) NOT NULL,
  `group_type_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `path_template` varchar(255) DEFAULT NULL,
  `data` longtext
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `type`
--

INSERT INTO `type` (`id`, `group_type_id`, `title`, `path_template`, `data`) VALUES
(1, 1, 'type1', NULL, '{"fields":[{"label":"Date","field_type":"date","required":true,"field_options":{},"cid":"c2"},{"label":"Signature","field_type":"sign","required":true,"field_options":{},"cid":"c6"}]}');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1435243475, 1, 'Admin', 'istrator', 'ADMIN', '0');

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(4, 1, 2),
(5, 2, 2);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `certificate`
--
ALTER TABLE `certificate`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_219CDA4AA76ED395` (`user_id`);

--
-- Index pour la table `form`
--
ALTER TABLE `form`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_5288FD4FA76ED395` (`user_id`), ADD KEY `IDX_5288FD4FC54C8C93` (`type_id`);

--
-- Index pour la table `form_relation`
--
ALTER TABLE `form_relation`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_D3B3C20D934680C0` (`type_id_1`), ADD KEY `IDX_D3B3C20DA4FD17A` (`type_id_2`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `group_type`
--
ALTER TABLE `group_type`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modify_history`
--
ALTER TABLE `modify_history`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_75FB39F25FF69B7D` (`form_id`), ADD KEY `IDX_75FB39F2A76ED395` (`user_id`);

--
-- Index pour la table `send_history`
--
ALTER TABLE `send_history`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_F1F305255FF69B7D` (`form_id`), ADD KEY `IDX_F1F305252130303A` (`from_user_id`), ADD KEY `IDX_F1F3052529F6EE60` (`to_user_id`);

--
-- Index pour la table `share`
--
ALTER TABLE `share`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_EF069D5A5FF69B7D` (`form_id`), ADD KEY `IDX_EF069D5AA76ED395` (`user_id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_8CDE5729434CD89F` (`group_type_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_groups`
--
ALTER TABLE `users_groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`), ADD KEY `fk_users_groups_users1_idx` (`user_id`), ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `certificate`
--
ALTER TABLE `certificate`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `form`
--
ALTER TABLE `form`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `form_relation`
--
ALTER TABLE `form_relation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `group_type`
--
ALTER TABLE `group_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `modify_history`
--
ALTER TABLE `modify_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `send_history`
--
ALTER TABLE `send_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `share`
--
ALTER TABLE `share`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `users_groups`
--
ALTER TABLE `users_groups`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `certificate`
--
ALTER TABLE `certificate`
ADD CONSTRAINT `FK_219CDA4AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `form`
--
ALTER TABLE `form`
ADD CONSTRAINT `FK_5288FD4FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD CONSTRAINT `FK_5288FD4FC54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `form_relation`
--
ALTER TABLE `form_relation`
ADD CONSTRAINT `FK_D3B3C20D934680C0` FOREIGN KEY (`type_id_1`) REFERENCES `type` (`id`),
ADD CONSTRAINT `FK_D3B3C20DA4FD17A` FOREIGN KEY (`type_id_2`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `modify_history`
--
ALTER TABLE `modify_history`
ADD CONSTRAINT `FK_75FB39F25FF69B7D` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`),
ADD CONSTRAINT `FK_75FB39F2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `send_history`
--
ALTER TABLE `send_history`
ADD CONSTRAINT `FK_F1F305252130303A` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
ADD CONSTRAINT `FK_F1F3052529F6EE60` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`),
ADD CONSTRAINT `FK_F1F305255FF69B7D` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`);

--
-- Contraintes pour la table `share`
--
ALTER TABLE `share`
ADD CONSTRAINT `FK_EF069D5A5FF69B7D` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`),
ADD CONSTRAINT `FK_EF069D5AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `type`
--
ALTER TABLE `type`
ADD CONSTRAINT `FK_8CDE5729434CD89F` FOREIGN KEY (`group_type_id`) REFERENCES `group_type` (`id`);

--
-- Contraintes pour la table `users_groups`
--
ALTER TABLE `users_groups`
ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
