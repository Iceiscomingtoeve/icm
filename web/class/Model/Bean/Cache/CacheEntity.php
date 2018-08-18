<?php

namespace Model\Bean\Cache;

use EVEOnline\ESI\Utils\Enums\EntityType;
use EVEOnline\ESI\Utils\SimpleEntityInfo;
use Model\Expression\SqlExpression;
use Model\Expression\Where\In;
use Model\Model;
use Model\MySQL;

/**
 * Class CacheEntity
 *
 * @package Model\Bean\Cache
 */
class CacheEntity extends Model {

	/**
	 * @var int $id_entity the entity ID (from EVE Online)
	 */
	public $id_entity = NULL;

	/**
	 * @var string $name the name of the entity
	 */
	public $name = "";

	/**
	 * @var EntityType $entity_type the entity type
	 */
	public $entity_type = 0;

	/**
	 * CacheEntity constructor.
	 */
	public function __construct() {
		parent::__construct(
			DB_NAME . "." . "`cache_entity`",
			array(),
			"id_entity"
		);
	}

	/**
	 * @return SimpleEntityInfo the SimpleEntityInfo according to the current CacheEntity
	 */
	public function getEntityInfo(): SimpleEntityInfo {
		return new SimpleEntityInfo(
			$this->id_entity,
			$this->name,
			$this->entity_type
		);
	}

	/**
	 * Creates a CacheEntity according to the given SimpleEntityInfo.
	 *
	 * @param SimpleEntityInfo $entityInfo the SimpleEntityInfo
	 * @return CacheEntity the CacheEntity ready to insert/update/else
	 */
	public static function createCacheEntityFromEntityInfo(SimpleEntityInfo $entityInfo): CacheEntity {
		$cacheEntity = new CacheEntity();
		$cacheEntity->id_entity = $entityInfo->getId();
		$cacheEntity->name = $entityInfo->getName();
		$cacheEntity->entity_type = $entityInfo->getType();
		return $cacheEntity;
	}

	/**
	 * Retrieves the CacheEntity from the given EVEOnline entity id.
	 *
	 * @param array $entityIds the EVEOnline entity ID
	 * @return CacheEntity[] the CacheEntity matching given IDs
	 */
	public static function getEntityFromEntityId(array $entityIds): array {
		if (empty($entityIds)) {
			return array();
		}

		$ret = self::getEntityFromFromSqlExpression(new In("id_entity", $entityIds), $entityIds);
		if ($ret !== false && !empty($ret)) {
			return $ret;
		}
		return array();
	}

	/**
	 * Retrieves the CacheEntity from the given Sql Expression
	 *
	 * @param SqlExpression $sqlExpression the Sql Expression
	 * @param array $values the binding array (empty by default)
	 * @return CacheEntity[] the CacheEntity matching the Sql expression
	 */
	public static function getEntityFromFromSqlExpression(
		SqlExpression $sqlExpression,
		array $values = array()
	): array {
		if (is_null($sqlExpression)) {
			return array();
		}

		$sqlQuery = "
			SELECT
				*
			FROM
				" . DB_NAME . ".`cache_entity`
			WHERE
				" . $sqlExpression->toSql() . "
			;";
		$db = new MySQL();
		/** @var CacheEntity[] $entities */
		$entities = $db->objExec($sqlQuery, __CLASS__, array_values($values));
		if ($entities !== false && !empty($entities)) {
			foreach ($entities as $entity) {
				$entity->replaceStringByEnums("entity_type", EntityType::class);
			}
		}
		return $entities;
	}

}
