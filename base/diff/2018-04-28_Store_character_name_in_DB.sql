ALTER TABLE `oauth2_users`
	ADD COLUMN `character_name` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id_forum_user`,
	ADD COLUMN `is_main_character` TINYINT(1) NOT NULL DEFAULT '0' AFTER `character_name`;

CREATE TABLE `user_session` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_forum_user` int(11) NOT NULL,
	`main_character_id` int(11) NOT NULL,
	`current_page` varchar(255) NOT NULL DEFAULT '/',
	PRIMARY KEY (`id`),
	UNIQUE KEY `user_id_UNIQUE` (`id_forum_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
