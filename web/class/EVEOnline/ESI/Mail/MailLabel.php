<?php

namespace EVEOnline\ESI\Mail;
use EVEOnline\ESI\EsiFactory;
use Model\Bean\OAuth2Users;
use Model\Bean\UserSession;
use Utils\Exceptions\IllegalAccessException;

/**
 * Class MailLabel
 *
 * @package EVEOnline\ESI\Mail
 */
class MailLabel {

	/**
	 * @var int
	 */
	private $labelId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $color;

	/**
	 * @var int
	 */
	private $unreadCount;

	/**
	 * MailLabel constructor.
	 *
	 * @param int $labelId
	 * @param string $name
	 * @param string $color
	 * @param int $unreadCount
	 */
	public function __construct(
		int $labelId,
		string $name,
		string $color,
		int $unreadCount
	) {
		$this->labelId = $labelId;
		$this->name = $name;
		$this->color = $color;
		$this->unreadCount = $unreadCount;
	}

	/**
	 * @return int
	 */
	public function getLabelId(): int {
		return $this->labelId;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getColor(): string {
		return $this->color;
	}

	/**
	 * @return int
	 */
	public function getUnreadCount(): int {
		return $this->unreadCount;
	}

	/**
	 * Creates a MailLabel from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return MailLabel
	 */
	public static function create(array $json) {
		return new MailLabel(
			$json['label_id'],
			$json['name'],
			$json['color'],
			array_key_exists("unread_count", $json) ? $json['unread_count'] : 0
		);
	}

	/**
	 * Retrieve MailLabel of the given character.
	 *
	 * @param OAuth2Users|null $oauthUser the OAuth2Users (if null, will ge the active character)
	 * @return MailLabel[]
	 */
	public static function invoke(?OAuth2Users $oauthUser) {
		if (is_null($oauthUser)) {
			$activeCharacter = UserSession::getSession()->getActiveCharacter();
			if (is_null($activeCharacter)) {
				throw new IllegalAccessException("No active character, can't retrieve mail labels");
			} else if (is_null($activeCharacter->getOauthUser())) {
				throw new IllegalAccessException("Selected user is not authenticated with ESI");
			}
			$oauthUser = $activeCharacter->getOauthUser();
		}

		$res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/mail/labels/",
			array("character_id" => $oauthUser->id_entity)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		$mailLabels = array();
		foreach ($json['labels'] as $label) {
			$mailLabels[] = MailLabel::create($label);
		}
		return $mailLabels;
	}

}
