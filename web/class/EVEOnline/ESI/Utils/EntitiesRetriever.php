<?php

namespace EVEOnline\ESI\Utils;

use EVEOnline\ESI\EsiFactory;
use Model\Bean\Cache\CacheEntity;

/**
 * Helps to retrieve entities's name from their ID.
 *
 * @package EVEOnline\ESI\Utils
 */
class EntitiesRetriever {

	/** @var array */
	private static $ENTITY_INFO = array();

	/**
	 * Retrieves entity information and cache them for next sessions.
	 *
	 * @param SimpleEntityInfo[] $entities to complete with its ID as the key of the array
	 * @return SimpleEntityInfo[] the SimpleEntityInfo array or empty
	 */
	public static function getEntityInfo(
		array &$entities
	): array {
		if (!is_array($entities) || empty($entities)) {
			return array();
		}

		$idsToFetch = array();
		foreach ($entities as $entityId => $entity) {
			// Prepare the cache if not existing yet
			if (!array_key_exists($entityId, self::$ENTITY_INFO)) {
				self::$ENTITY_INFO[$entityId] = $entity;
				$idsToFetch[intval($entityId)] = intval($entityId);
			}
		}

		// If the local cache is empty, try to retrieve them from DB
		$cachedEntities = CacheEntity::getEntityFromEntityId(array_values($idsToFetch));
		foreach ($cachedEntities as $cachedEntity) {
			if (!empty($cachedEntity->name)) {
				unset($idsToFetch[$cachedEntity->id_entity]);
			}
		}

		// If some entity are still missing, retrieve them from ESI and store them in DB
		if (!empty($idsToFetch)) {
			// Sets the parameters
			$res = EsiFactory::invoke(
				null,
				"post",
				"/universe/names/",
				array(),
				array(),
				$idsToFetch
			);
			$json = json_decode($res->raw, true);
			foreach ($json as $entity) {
				$entity = SimpleEntityInfo::create($entity);
				// Update back the array which was given (save time)
				$entities[$entity->getId()]->setName($entity->getName());

				// Insert in Database the entity
				CacheEntity::createCacheEntityFromEntityInfo($entity)->insert();
				// And update cache
				self::$ENTITY_INFO[$entity->getId()] = $entity;
			}
		}
		return $entities;
	}

}
