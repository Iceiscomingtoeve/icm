<?php

namespace Model\Table;

use Model\Expression\SqlExpression;
use Model\Expression\Where\Equal;
use Model\Expression\Where\IsTrue;
use Model\Model;
use Model\MySQL;
use Utils\Handler\PhpBB;

/**
 * Class OAuth2Users
 *
 * @package Model\table
 */
class OAuth2Users extends Model {

	/**
	 * @var integer $id the primary ID
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
	 * @var integer $expire_time the expiration time
	 */
	public $expire_time = -1;

	/**
	 * @var integer $id_character the character ID of EVE
	 */
	public $id_character = NULL;

	/**
	 * @var integer $id_forum_user the forum user ID
	 */
	public $id_forum_user = NULL;

	/**
	 * OAuth2Users constructor.
	 */
	public function __construct() {
		parent::__construct(
			DB_NAME . "." . "`oauth2_users`",
			array("id_character", "id_forum_user"),
			"id"
		);
	}

	/**
	 * Retrieves all the OAuth2Users.
	 *
	 * @return OAuth2Users[] Every registered character
	 */
	public static function getAllCharacters() {
		return self::getCharacterFromSqlExpression(new IsTrue());
	}

	/**
	 * Retrieves the OAuth2Users from the given user id (forum user).
	 *
	 * @param integer $userId the forum user ID (by default the connected user)
	 * @return OAuth2Users[] Every registered character
	 */
	public static function getCharacterFromUserId($userId = NULL) {
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
	 * @param integer $characterId the EVEOnline character ID
	 * @return OAuth2Users the registered character
	 */
	public static function getCharacterFromCharacterId($characterId) {
		if (is_null($characterId) || !is_numeric($characterId) || $characterId <= 0) {
			return null;
		}

		$ret = self::getCharacterFromSqlExpression(new Equal("id_character"), array($characterId));
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
			;";
		$db = new MySQL();
		return $db->objExec($sqlQuery, __CLASS__, $values);
	}

}
