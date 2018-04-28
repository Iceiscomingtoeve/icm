<?php

namespace EVEOnline\ESI\Character;

/**
 * Class Channel
 *
 * @package EVEOnline\ESI\Character
 */
final class Channel {

	/**
	 * @var int
	 */
	private $channelId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var int
	 */
	private $ownerId;

	/**
	 * @var string
	 */
	private $comparisonKey;

	/**
	 * @var bool
	 */
	private $hasPassword;

	/**
	 * @var string
	 */
	private $motd;

	/**
	 * @var ChannelDetails[]
	 */
	private $allowed;

	/**
	 * @var ChannelDetails[]
	 */
	private $operators;

	/**
	 * @var ChannelDetails[]
	 */
	private $blocked;

	/**
	 * @var ChannelDetails[]
	 */
	private $muted;

	/**
	 * Channel constructor.
	 *
	 * @param int $channelId
	 * @param string $name
	 * @param int $ownerId
	 * @param string $comparisonKey
	 * @param bool $hasPassword
	 * @param string $motd
	 * @param ChannelDetails[] $allowed
	 * @param ChannelDetails[] $operators
	 * @param ChannelDetails[] $blocked
	 * @param ChannelDetails[] $muted
	 */
	public function __construct(
		int $channelId,
		string $name,
		int $ownerId,
		string $comparisonKey,
		bool $hasPassword,
		string $motd,
		array $allowed,
		array $operators,
		array $blocked,
		array $muted
	) {
		$this->channelId = $channelId;
		$this->name = $name;
		$this->ownerId = $ownerId;
		$this->comparisonKey = $comparisonKey;
		$this->hasPassword = $hasPassword;
		$this->motd = $motd;
		$this->allowed = $allowed;
		$this->operators = $operators;
		$this->blocked = $blocked;
		$this->muted = $muted;
	}

	/**
	 * Creates a Channel from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return Channel
	 */
	public static function create(array $json) {
		return new Channel(
			$json['channel_id'],
			$json['name'],
			$json['owner_id'],
			$json['comparison_key'],
			$json['has_password'],
			$json['motd'],
			self::createChannelDetails($json, "allowed"),
			self::createChannelDetails($json, "operators"),
			self::createChannelDetails($json, "blocked"),
			self::createChannelDetails($json, "muted")
		);
	}

	/**
	 * Creates an array of ChannelDetails.
	 *
	 * @param array $json the global json
	 * @param string $channelType the type of channel to retrieve
	 * @return array the array (can be empty) of ChannelDetails
	 */
	private static function createChannelDetails(array $json, string $channelType) {
		$channels = array();
		// Retrieves channels if exists
		if (array_key_exists($channelType, $json)) {
			foreach ($json[$channelType] as $curChannel) {
				$channels[] = ChannelDetails::create($curChannel);
			}
		}
		return $channels;
	}

}
