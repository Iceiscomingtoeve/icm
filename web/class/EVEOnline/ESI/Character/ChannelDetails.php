<?php

namespace EVEOnline\ESI\Character;
use EVEOnline\ESI\Character\Enums\AccessorType;

/**
 * Class ChannelDetails
 *
 * @package EVEOnline\ESI\Character
 */
final class ChannelDetails {

	/**
	 * @var int
	 */
	private $accessorId;

	/**
	 * @var AccessorType
	 */
	private $accessorType;

	/**
	 * @var string
	 */
	private $reason;

	/**
	 * @var string
	 */
	private $endAt;

	/**
	 * ChannelDetails constructor.
	 *
	 * @param int $accessorId
	 * @param AccessorType $accessorType
	 * @param string $reason
	 * @param string $endAt
	 */
	public function __construct(
		int $accessorId,
		AccessorType $accessorType,
		string $reason,
		string $endAt
	) {
		$this->accessorId = $accessorId;
		$this->accessorType = $accessorType;
		$this->reason = $reason;
		$this->endAt = $endAt;
	}

	/**
	 * @return int
	 */
	public function getAccessorId() {
		return $this->accessorId;
	}

	/**
	 * @return AccessorType
	 */
	public function getAccessorType() {
		return $this->accessorType;
	}

	/**
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * @return string
	 */
	public function getEndAt() {
		return $this->endAt;
	}

	/**
	 * Creates a ChannelDetails from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return ChannelDetails
	 */
	public static function create(array $json) {
		return new ChannelDetails(
			$json['accessor_id'],
			new AccessorType($json['accessor_type']),
			array_key_exists("reason", $json) ? $json['reason'] : "",
			array_key_exists("end_at", $json) ? $json['end_at'] : ""
		);
	}

}
