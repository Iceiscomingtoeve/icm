<?php

namespace Model\Bean;

use EVEOnline\OAuth\TokenType;
use Model\Expression\SqlExpression;
use Model\Expression\Where\Equal;
use Model\Model;
use Model\MySQL;
use Utils\Handler\PhpBB;

/**
 * Class OAuth2Users
 *
 * @package Model\Bean
 */
class OAuth2Users extends Model {

	/**
	 * @var int $id the primary ID
	 */
	public $id = NULL;

	/**
	 * @var string $access_token the access token
	 */
	public $access_token = "";

	/**
	 * @var string $refresh_token the refresh token
	 */
	public $refresh_token = "";

	/**
	 * @var int $expire_time the expiration time
	 */
	public $expire_time = -1;

	/**
	 * @var TokenType $token_type the token type
	 */
	public $token_type = NULL;

	/**
	 * @var int|null $id_entity the entity (character, corp, alliance) ID of EVE
	 */
	public $id_entity = NULL;

	/**
	 * @var int $id_forum_user the user ID from PhpBB
	 */
	public $id_forum_user = ANONYMOUS;

	/**
	 * @var string $entity_name the name of the character
	 */
	public $entity_name = "";

	/**
	 * @var bool $is_main_character is he the main character ?
	 */
	public $is_main_character = false;

	/**
	 * OAuth2Users constructor.
	 */
	public function __construct() {
		parent::__construct(
			DB_NAME . "." . "`oauth2_users`",
			array("id_entity", "id_forum_user"),
			"id"
		);
	}

	/**
	 * Retrieves all the OAuth2Users which is a character.
	 *
	 * @return OAuth2Users[] Every registered character
	 */
	public static function getAllCharacters() {
		return self::getCharacterFromSqlExpression(
			new Equal("token_type"),
			array(new TokenType(TokenType::CHARACTER))
		);
	}

	/**
	 * Retrieves the OAuth2Users from the given user id (forum user).
	 *
	 * @param int $userId the forum user ID (by default the connected user)
	 * @return OAuth2Users[] Every registered character
	 */
	public static function getCharacterFromUserId(int $userId = NULL) {
		if (is_null($userId)) {
			$userId = PhpBB::getInstance()->getUser()->data['user_id'];
		}

		if (!is_numeric($userId) || $userId <= 0) {
			return array();
		}

		return self::getCharacterFromSqlExpression(new Equal("id_forum_user"), array($userId));
	}

	/**
	 * Retrieves the OAuth2Users from the given EVEOnline character id.
	 *
	 * @param int $characterId the EVEOnline character ID
	 * @return OAuth2Users the registered character
	 */
	public static function getCharacterFromCharacterId(int $characterId) {
		if (is_null($characterId) || !is_numeric($characterId) || $characterId <= 0) {
			return NULL;
		}

		$ret = self::getCharacterFromSqlExpression(new Equal("id_entity"), array($characterId));
		if ($ret !== false && !empty($ret)) {
			return $ret[0];
		}
		return NULL;
	}

	/**
	 * Retrieves the OAuth2Users from the given Sql Expression
	 *
	 * @param SqlExpression $sqlExpression the Sql Expression
	 * @param array $values the binding array (empty by default)
	 * @return OAuth2Users[] the user matching the Sql expression
	 */
	public static function getCharacterFromSqlExpression(
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
				" . DB_NAME . ".`oauth2_users`
			WHERE
				" . $sqlExpression->toSql() . "
			ORDER BY
				is_main_character DESC, entity_name ASC
			;";
		$db = new MySQL();
		/** @var OAuth2Users[] $users */
		$users = $db->objExec($sqlQuery, __CLASS__, $values);
		if ($users !== false && !empty($users)) {
			foreach ($users as $user) {
				$user->replaceStringByEnums("token_type", TokenType::class);
			}
		}
		return $users;
	}

}
