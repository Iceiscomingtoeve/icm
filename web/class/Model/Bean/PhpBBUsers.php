<?php

namespace Model\Bean;

use Model\Expression\Where\Equal;
use Model\Expression\Where\In;
use Model\Model;
use Model\MySQL;
use Utils\Exceptions\IllegalAccessException;

/**
 * Class PhpBBUsers
 *
 * @package Model\Bean
 */
class PhpBBUsers extends Model {

	/**
	 * @var int $user_id the primary ID
	 */
	public $user_id = NULL;

	/**
	 * @var int $user_type the user type see https://wiki.phpbb.com/Table.phpbb_users
	 */
	public $user_type = 0;

	/**
	 * @var string $username the username
	 */
	public $username = "";

	/**
	 * @var int $user_lastvisit timestamp when user visit the forum for the last time
	 */
	public $user_lastvisit = 0;

	/** @var OAuth2Users[] $characters list of OAuth2Users */
	public $characters;

	/**
	 * PhpBBUsers constructor.
	 */
	public function __construct() {
		parent::__construct("", array(), "user_id");
	}

	private static function getDatabaseConnection(): MySQL {
		require_once PATH_PHPBB . "/config.php";
		global $dbhost, $dbname, $dbport, $dbuser, $dbpasswd;
		return new MySQL(
			$dbhost,
			$dbname,
			$dbport,
			$dbuser,
			$dbpasswd
		);
	}

	public function insert(bool $ignore = false): bool {
		throw new IllegalAccessException("You cannot create a PhpBB user");
	}

	public function update(): bool {
		throw new IllegalAccessException("You cannot update a PhpBB user");
	}

	public function delete(): bool {
		throw new IllegalAccessException("You cannot delete a PhpBB user");
	}

	/**
	 * Retrieves all the PhpBBUsers who are active or is anonymous.
	 *
	 * @return PhpBBUsers[] Every active user
	 */
	public static function getAllCharacters() {
		// see https://wiki.phpbb.com/Table.phpbb_users
		// type 0 is registered and 3 is founder (super admin)
		$activeTypes = array(0, 3);
		require_once PATH_PHPBB . "/config.php";
		global $dbname, $table_prefix;

		$sqlQuery = "
			SELECT
				*
			FROM
				" . $dbname . ".`" . $table_prefix . "users`
			WHERE
				" . new In("user_type", $activeTypes) . "
					OR
				" . new Equal("user_id") . "
			ORDER BY
				user_id ASC
			;";
		$db = self::getDatabaseConnection();
		return $db->objExec($sqlQuery, __CLASS__, array_merge($activeTypes, array(ANONYMOUS)));
	}

}
