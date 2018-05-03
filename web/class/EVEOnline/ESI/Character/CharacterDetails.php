<?php

namespace EVEOnline\ESI\Character;

use EVEOnline\ESI\Character\Enums\Gender;

/**
 * Class CharacterDetails
 *
 * @package EVEOnline\ESI\Character
 */
final class CharacterDetails {

	/**
	 * @var int
	 */
	private $characterId;

	/**
	 * @var string
	 */
	private $birthday;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Gender
	 */
	private $gender;

	/**
	 * @var string
	 */
	private $biography;

	/**
	 * @var int
	 */
	private $raceId;

	/**
	 * @var int
	 */
	private $bloodLineId;

	/**
	 * @var int
	 */
	private $corporationId;

	/**
	 * @var int
	 */
	private $allianceId;

	/**
	 * @var int
	 */
	private $ancestryId;

	/**
	 * @var float
	 */
	private $securityStatus;

	/**
	 * @var int
	 */
	private $factionId;

	/**
	 * CharacterDetails constructor.
	 *
	 * @param int $characterId
	 * @param string $birthday
	 * @param string $name
	 * @param Gender $gender
	 * @param string $biography
	 * @param int $raceId
	 * @param int $bloodLineId
	 * @param int $ancestryId
	 * @param float $securityStatus
	 * @param int $corporationId
	 * @param int|null $allianceId
	 * @param int|null $factionId
	 */
	public function __construct(
		int $characterId,
		string $birthday,
		string $name,
		Gender $gender,
		string $biography,
		int $raceId,
		int $bloodLineId,
		int $ancestryId,
		float $securityStatus,
		int $corporationId,
		$allianceId = NULL,
		$factionId = NULL
	) {
		$this->characterId = $characterId;
		$this->birthday = $birthday;
		$this->name = $name;
		$this->gender = $gender;
		$this->biography = $biography;
		$this->raceId = $raceId;
		$this->bloodLineId = $bloodLineId;
		$this->ancestryId = $ancestryId;
		$this->securityStatus = $securityStatus;
		$this->corporationId = $corporationId;
		$this->allianceId = $allianceId;
		$this->factionId = $factionId;
	}

	/**
	 * @return int
	 */
	public function getCharacterId() {
		return $this->characterId;
	}

	/**
	 * @return string
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return Gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return string
	 */
	public function getBiography() {
		return $this->biography;
	}

	/**
	 * @return int
	 */
	public function getRaceId() {
		return $this->raceId;
	}

	/**
	 * @return int
	 */
	public function getBloodLineId() {
		return $this->bloodLineId;
	}

	/**
	 * @return int
	 */
	public function getAncestryId() {
		return $this->ancestryId;
	}

	/**
	 * @return float
	 */
	public function getSecurityStatus() {
		return $this->securityStatus;
	}

	/**
	 * @return int
	 */
	public function getCorporationId() {
		return $this->corporationId;
	}

	/**
	 * @return int
	 */
	public function getAllianceId() {
		return $this->allianceId;
	}

	/**
	 * @return bool
	 */
	public function isInFactionalWarfare() {
		return !is_null($this->factionId);
	}

	/**
	 * @return int
	 */
	public function getFactionId() {
		return $this->factionId;
	}

	/**
	 * Creates a CharacterDetails from the associative json array.
	 *
	 * @param int $characterId the character ID
	 * @param array $json the json associative array
	 * @return CharacterDetails
	 */
	public static function create($characterId, array $json) {
		return new CharacterDetails(
			$characterId,
			$json['birthday'],
			$json['name'],
			new Gender($json['gender']),
			$json['description'],
			$json['race_id'],
			$json['ancestry_id'],
			$json['bloodline_id'],
			$json['security_status'],
			$json['corporation_id'],
			array_key_exists("alliance_id", $json) ? $json['alliance_id'] : NULL,
			array_key_exists("faction_id", $json) ? $json['faction_id'] : NULL
		);
	}

}
