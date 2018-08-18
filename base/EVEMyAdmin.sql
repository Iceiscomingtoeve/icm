-- If the character set of the schema is not utf8mb4, please run the below query
-- ALTER SCHEMA `evemyadmin` DEFAULT CHARACTER SET utf8mb4 ;

CREATE TABLE `oauth2_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`access_token` varchar(1024) NOT NULL DEFAULT '',
	`refresh_token` varchar(1024) NOT NULL DEFAULT '',
	`expire_time` int(11) NOT NULL DEFAULT '-1',
	`token_type` varchar(255) NOT NULL DEFAULT 'character',
	`id_entity` int(11) NOT NULL,
	`id_forum_user` int(11) NOT NULL DEFAULT '1',
	`entity_name` varchar(255) NOT NULL DEFAULT '',
	`is_main_character` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `oauth2_users_UNIQUE` (`id_entity`),
	KEY `fk_oauth_users_phpbb_forum_idx` (`id_forum_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_session` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_forum_user` int(11) NOT NULL,
	`main_character_id` int(11) NOT NULL,
	`current_page` varchar(255) NOT NULL DEFAULT '/',
	PRIMARY KEY (`id`),
	UNIQUE KEY `user_id_UNIQUE` (`id_forum_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache_entity` (
	`id_entity` int(11) NOT NULL,
	`name` varchar(512) NOT NULL DEFAULT '',
	`entity_type` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id_entity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
