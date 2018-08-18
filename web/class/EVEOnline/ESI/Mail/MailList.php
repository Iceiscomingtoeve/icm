<?php

namespace EVEOnline\ESI\Mail;
use EVEOnline\ESI\EsiFactory;
use Model\Bean\OAuth2Users;
use Model\Bean\UserSession;
use Utils\Exceptions\IllegalAccessException;

/**
 * Class MailList
 *
 * @package EVEOnline\ESI\Mail
 */
class MailList {

	/**
	 * @var int
	 */
	private $mailingListId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * MailList constructor.
	 *
	 * @param int $mailingListId
	 * @param string $name
	 */
	public function __construct(
		int $mailingListId,
		string $name
	) {
		$this->mailingListId = $mailingListId;
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getMailingListId(): int {
		return $this->mailingListId;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Creates a MailList from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return MailList
	 */
	public static function create(array $json) {
		return new MailList(
			$json['mailing_list_id'],
			$json['name']
		);
	}

	/**
	 * Retrieve MailLabel of the given character.
	 *
	 * @param OAuth2Users|null $oauthUser the OAuth2Users (if null, will ge the active character)
	 * @return MailList[]
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
			"/characters/{character_id}/mail/lists/",
			array("character_id" => $oauthUser->id_entity)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		$mailLists = array();
		foreach ($json as $list) {
			$mailLists[] = MailList::create($list);
		}
		return $mailLists;
	}

}
