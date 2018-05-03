ALTER TABLE `evemyadmin`.`oauth2_users`
	CHANGE COLUMN `id_character` `id_entity` INT(11) NOT NULL,
	CHANGE COLUMN `id_forum_user` `id_forum_user` INT(11) NOT NULL DEFAULT 1 ,
	CHANGE COLUMN `character_name` `entity_name` VARCHAR(255) NOT NULL DEFAULT '',
	ADD COLUMN `token_type` VARCHAR(255) NOT NULL DEFAULT 'CHARACTER' AFTER `expire_time`;
