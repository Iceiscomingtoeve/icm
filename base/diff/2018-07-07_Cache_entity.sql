CREATE TABLE `cache_entity` (
	`id_entity` int(11) NOT NULL,
	`name` varchar(512) NOT NULL DEFAULT '',
	`entity_type` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id_entity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
