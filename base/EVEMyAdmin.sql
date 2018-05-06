CREATE TABLE `oauth2_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(1024) NOT NULL DEFAULT '',
  `refresh_token` varchar(1024) NOT NULL DEFAULT '',
  `expire_time` int(11) NOT NULL DEFAULT '-1',
  `token_type` varchar(255) NOT NULL DEFAULT 'CHARACTER',
  `id_entity` int(11) NOT NULL,
  `id_forum_user` int(11) NOT NULL DEFAULT '1',
  `entity_name` varchar(255) NOT NULL DEFAULT '',
  `is_main_character` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth2_users_UNIQUE` (`id_entity`),
  KEY `fk_oauth_users_phpbb_forum_idx` (`id_forum_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_forum_user` int(11) NOT NULL,
  `main_character_id` int(11) NOT NULL,
  `current_page` varchar(255) NOT NULL DEFAULT '/',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`id_forum_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
