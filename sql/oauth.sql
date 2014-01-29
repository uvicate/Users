#Main structure

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `commercial_name` varchar(100) NOT NULL,
  `real_name` varchar(200) NOT NULL,
  `logo` varchar(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commercial_name` (`commercial_name`),
  UNIQUE KEY `real_name` (`real_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NULL,
  `password` varchar(255) NULL,
  `name` varchar(50) NOT NULL,
  `name2` varchar(50) NULL,
  `lastname` varchar(50) NOT NULL,
  `lastname2` varchar(50) NULL,
  `active` int(1) NOT NULL default 1,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_organizations` (
  `users_id` bigint(20) NOT NULL,
  `organizations_id` int(10) NOT NULL,
  KEY `users_id` (`users_id`),
  KEY `organizations_id` (`organizations_id`),
  CONSTRAINT `users_users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `organizations_organizations_id` FOREIGN KEY (`organizations_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
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

CREATE TABLE IF NOT EXISTS `oauth_client_types` (
  `id_client_type` int(10) NOT NULL AUTO_INCREMENT,
  `client_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id_client_type`),
  UNIQUE KEY `client_type` (`client_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `oauth_client_types` (`client_type`) VALUES ('trusted'), ('public'), ('banned');

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id` VARCHAR(80) NOT NULL, 
  `client_secret` VARCHAR(80) NOT NULL, 
  `redirect_uri` VARCHAR(2000) NOT NULL, 
  `grant_types` VARCHAR(80),
  `id_client_type` int(10) NOT NULL DEFAULT 2,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_secret` (`client_secret`),
  KEY `id_client_type` (`id_client_type`),
  CONSTRAINT `oauth_clients_id_client_type` FOREIGN KEY (`id_client_type`) REFERENCES `oauth_client_types` (`id_client_type`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `id_client_type`) VALUES ('User Manager', '2412JZIlMMpRX3s27O432fNZ2D21rhDH', 'http://localhost', 1);

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
  `id_oauth_scope` int(10) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(255) NOT NULL DEFAULT "supported", 
  `scope` VARCHAR(2000), 
  `client_id` VARCHAR (80),
  PRIMARY KEY (`id_oauth_scope`),
  KEY `client_id` (`client_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE 
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `oauth_scopes` (`type`, `scope`) VALUES ('supported', 'basic_profile'), ('supported', 'full_profile'), ('supported', 'read_users'), ('supported', 'write_users'), ('supported', 'delete_users'), ('supported', 'revoke_credentials'), ('supported', 'read_user_log_history'), ('supported', 'read_catalog'), ('supported', 'write_catalog');

CREATE TABLE IF NOT EXISTS `oauth_client_type_scopes` (
  `id_oauth_client_type_scope` int(10) NOT NULL AUTO_INCREMENT,
  `id_oauth_scope` int(10) NOT NULL,
  `id_client_type` int(10) NOT NULL,
  PRIMARY KEY (`id_oauth_client_type_scope`),
  KEY `id_oauth_scope` (`id_oauth_scope`),
  KEY `id_client_type` (`id_client_type`),
  CONSTRAINT `oauth_client_scopes_id_oauth_scope` FOREIGN KEY (`id_oauth_scope`) REFERENCES `oauth_scopes` (`id_oauth_scope`) ON DELETE CASCADE,
  CONSTRAINT `oauth_client_scopes_id_client_type` FOREIGN KEY (`id_client_type`) REFERENCES `oauth_client_types` (`id_client_type`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `oauth_client_type_scopes` (`id_client_type`, `id_oauth_scope`) VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (2, 1), (2, 2);

CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` VARCHAR(80) NOT NULL, 
  `subject` VARCHAR(80), 
  `public_key` VARCHAR(2000),
  PRIMARY KEY (client_id),
  KEY `client_id` (`client_id`),
  CONSTRAINT FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;