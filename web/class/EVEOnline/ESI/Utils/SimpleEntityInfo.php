<?php

namespace EVEOnline\ESI\Utils;

use EVEOnline\ESI\Utils\Enums\EntityType;

/**
 * Stores simple information of the entity: ID and name.
 *
 * @package EVEOnline\ESI\Utils
 */
class SimpleEntityInfo {

	/** @var int */
	private $id;

	/** @var string */
	private $name;

	/** @var EntityType */
	private $type;

	/**
	 * SimpleEntityInfo constructor.
	 *
	 * @param int $id
	 * @param string $name
	 * @param EntityType $type
	 */
	public function __construct(
		int $id,
		string $name,
		EntityType $type
	) {
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return EntityType
	 */
	public function getType(): EntityType {
		return $this->type;
	}

	/**
	 * Creates a SimpleEntityInfo from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return SimpleEntityInfo
	 */
	public static function create(array $json) {
		return new SimpleEntityInfo(
			$json['id'],
			$json['name'],
			new EntityType($json['category'])
		);
	}

}
