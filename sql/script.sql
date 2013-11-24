CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `name2` varchar(50) NULL,
  `lastname` varchar(50) NOT NULL,
  `lastname2` varchar(50) NULL,
  `active` int(1) NOT NULL default 1,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `login` (
  `idLogin` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `expiracy` datetime NOT NULL,
  `keypass` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `ip` varchar(30) NULL,
  `os` varchar(30) NULL,
  `screen_resolution` varchar(30) NULL,
  PRIMARY KEY (`idLogin`),
  KEY `user_id` (`user_id`),
  UNIQUE KEY `keypass` (`keypass`),
  CONSTRAINT `login_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `forgotten_password` (
  `idForgotten` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `expiracy` datetime NOT NULL,
  `keypass` varchar(255) NOT NULL,
  `recovered` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idForgotten`),
  KEY `user_id` (`user_id`),
  UNIQUE KEY `keypass` (`keypass`),
  CONSTRAINT `forgottern_password_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id` VARCHAR(80) NOT NULL, 
  `client_secret` VARCHAR(80) NOT NULL, 
  `redirect_uri` VARCHAR(2000) NOT NULL, 
  `grant_tpyes` VARCHAR(80), 
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_secret` (`client_secret`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` VARCHAR(40) NOT NULL, 
  `client_id` VARCHAR(80) NOT NULL, 
  `user_id` bigint(20), 
  `expires` TIMESTAMP NOT NULL, 
  `scope` VARCHAR(2000), 
  PRIMARY KEY (`access_token`),
  KEY `client_id` (`client_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ,
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_authorization_codes` (
  `authorization_code` VARCHAR(40) NOT NULL, 
  `client_id` VARCHAR(80) NOT NULL, 
  `user_id` bigint(20), 
  `redirect_uri` VARCHAR(2000), 
  `expires` TIMESTAMP NOT NULL, 
  `scope` VARCHAR(2000), 
  PRIMARY KEY (`authorization_code`),
  KEY `client_id` (`client_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ,
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` VARCHAR(40) NOT NULL, 
  `client_id` VARCHAR(80) NOT NULL, 
  `user_id` bigint(20), 
  `expires` TIMESTAMP NOT NULL, 
  `scope` VARCHAR(2000), 
  PRIMARY KEY (refresh_token),
  KEY `client_id` (`client_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ,
  CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `type` VARCHAR(255) NOT NULL DEFAULT "supported", 
  `scope` VARCHAR(2000), 
  `client_id` VARCHAR (80),
  KEY `client_id` (`client_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE 
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` VARCHAR(80) NOT NULL, 
  `subject` VARCHAR(80), 
  `public_key` VARCHAR(2000),
  PRIMARY KEY (client_id),
  KEY `client_id` (`client_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;