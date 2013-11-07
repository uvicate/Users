CREATE TABLE IF NOT EXISTS `users` (
  `idUser` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `name2` varchar(50) NULL,
  `lastname` varchar(50) NOT NULL,
  `lastname2` varchar(50) NULL,
  `active` int(1) NOT NULL default 1,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
