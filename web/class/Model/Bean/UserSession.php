<?php

namespace Model\Bean;

use Model\Expression\SqlExpression;
use Model\Expression\Where\Equal;
use Model\Model;
use Model\MySQL;
use Model\Session\EVECharacter;
use Utils\Handler\PhpBB;

/**
 * Class UserSession
 *
 * @package Model\Bean
 */
class UserSession extends Model {

	/**
	 * @var UserSession the current UserSession
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * @var int $id the primary ID
	 */
	public $id = NULL;

	/**
	 * @var int $id_forum_user the user ID of the PhpBB
	 */
	public $id_forum_user = ANONYMOUS;

	/**
	 * @var int $main_character_id the main character ID from EVE
	 */
	public $main_character_id = 0;

	/**
	 * @var string $current_page the current page the user is on
	 */
	public $current_page = "/";

	// Cache fields to get precomputed data easier and faster
	/**
	 * @var EVECharacter|null the main character
	 */
	private $mainCharacter = NULL;
	/**
	 * @var EVECharacter[] all the user's characters
	 */
	private $characters = array();

	/**
	 * OAuth2Users constructor.
	 */
	public function __construct() {
		parent::__construct(
			DB_NAME . "." . "`user_session`",
			array("id_forum_user"),
			"id"
		);
	}

	/**
	 * Retrieves or creates the UserSession.
	 *
	 * @return UserSession
	 */
	public static function getSession(): UserSession {
		if (is_null(self::$INSTANCE)) {
			self::$INSTANCE = self::getSessionFromUserId(PhpBB::getInstance()->getUser()->data['user_id']);
		}
		return self::$INSTANCE;
	}

	/**
	 * Defines the current URI of the user.
	 *
	 * @param string $currentUri the current URI
	 */
	public final function setActiveUri(string $currentUri) {
		if (strcmp($this->current_page, $currentUri) != 0) {
			$this->current_page = $currentUri;
			if ($this->id_forum_user != ANONYMOUS) {
				$this->update();
			}
		}
	}

	/**
	 * Defines the current URI of the user.
	 *
	 * @return string the current URI
	 */
	public final function getActiveUri(): string {
		return $this->current_page;
	}

	/**
	 * Adds the given EVECharacter into the Session.
	 *
	 * @param EVECharacter $eveCharacter
	 */
	public final function setActiveCharacter(EVECharacter $eveCharacter) {
		if ($this->main_character_id != $eveCharacter->getCharacterId()) {
			$this->main_character_id = $eveCharacter->getCharacterId();
			if ($this->id_forum_user != ANONYMOUS) {
				$this->update();
			}
		}
	}

	/**
	 * Adds the given EVECharacter into the Session.
	 *
	 * @return EVECharacter|null the active character
	 */
	public final function getActiveCharacter() {
		// If not yet fetched or mismatching
		if (is_null($this->mainCharacter) ||
			$this->mainCharacter->getCharacterId() != $this->main_character_id
		) {
			$character = OAuth2Users::getCharacterFromCharacterId($this->main_character_id);
			if (!is_null($character)) {
				$this->mainCharacter = new EVECharacter($character);
			}
		}
		return $this->mainCharacter;
	}

	/**
	 * Adds the given EVECharacter into the Session.
	 *
	 * @param EVECharacter $eveCharacter
	 */
	public final function addEVECharacter(EVECharacter $eveCharacter) {
		$this->characters[] = $eveCharacter;
	}

	/**
	 * Retrieves the list of EVECharacter.
	 *
	 * @return EVECharacter[] the EVECharacter as an array (can be empty if not linked et or anonymous)
	 */
	public final function getEVECharacters(): array {
		return $this->characters;
	}

	/**
	 * Retrieves the UserSession from the given user id (forum user).
	 *
	 * @param int $userId the forum user ID (by default the connected user)
	 * @return UserSession|null the UserSession
	 */
	private static function getSessionFromUserId(int $userId): UserSession {
		// Wrong data given !
		if (!is_numeric($userId) || $userId <= 0) {
			return NULL;
		}

		// Do not save in DB the ANONYMOUS session (waste of time and resources)
		if ($userId == ANONYMOUS) {
			return new UserSession();
		}

		$ret = self::getSessionFromSqlExpression(new Equal("id_forum_user"), array($userId));
		if ($ret !== false && !empty($ret)) {
			return $ret[0];
		}
		// Does not exist yet ? Create it !
		$ret = new UserSession();
		$ret->id_forum_user = $userId;
		$characters = OAuth2Users::getCharacterFromUserId($userId);
		$ret->main_character_id = empty($characters) ? -1 : $characters[0]->id_entity;
		$ret->current_page = "/";
		$ret->insert();
		return $ret;
	}

	/**
	 * Retrieves the UserSession from the given Sql Expression
	 *
	 * @param SqlExpression $sqlExpression the Sql Expression
	 * @param array $values the binding array (empty by default)
	 * @return UserSession[] user session matching the Sql expression
	 */
	private static function getSessionFromSqlExpression(
		SqlExpression $sqlExpression,
		array $values = array()
	) {
		if (is_null($sqlExpression)) {
			return array();
		}

		$sqlQuery = "
			SELECT
				*
			FROM
				" . DB_NAME . ".`user_session`
			WHERE
				" . $sqlExpression->toSql() . "
			;";
		$db = new MySQL();
		return $db->objExec($sqlQuery, __CLASS__, $values);
	}

}
