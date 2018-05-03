<?php

namespace Utils\Handler;

/**
 * Handles the link with PhpBB.
 *
 * @package Utils\Handler
 */
final class PhpBB implements Handler {

	/**
	 * @var PhpBB
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * @var \phpbb\user $user the phpbb user
	 */
	private $user;

	/**
	 * @var \phpbb\request\request $request the way to retrieve value from GET and POST from PhpBB
	 */
	private $request;

	private function __construct() {
		/**
		 * @var \phpbb\user $user the phpbb user
		 */
		global $user;
		/**
		 * @var \phpbb\auth\auth $auth the auth
		 */
		global $auth;
		$this->user = $user;
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();

		/**
		 * @var \phpbb\request\request $auth the auth
		 */
		global $request;
		$this->request = $request;
	}

	/**
	 * Creates or retrieves the instance of PhpBB.
	 *
	 * @return PhpBB
	 */
	public static function getInstance() {
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}
		self::$INSTANCE = new PhpBB();
		return self::$INSTANCE;
	}

	/**
	 * @return \phpbb\user the connected user
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return \phpbb\request\request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return bool true if the user is not logged in, false otherwise
	 */
	public function isAnonymous() {
		//ANONYMOUS is a define on phpbb side which is equals to 1
		return $this->user->data['user_id'] == ANONYMOUS;
	}

	/**
	 * @return bool true if the user is a director, false otherwise
	 */
	public function isDirector() {
		return self::isUserInGroup($this->user->data['user_id'], PHPBB_GROUP_DIRECTOR_ID);
	}

	/**
	 * Is the given user in the given group ?
	 *
	 * @param int $userId the user ID
	 * @param int $groupId the group ID
	 * @return bool true if he/she is in, false otherwise
	 */
	public static function isUserInGroup(
		int $userId,
		int $groupId
	) {
		// Require for checking group from the user
		require_once PATH_PHPBB . "/includes/functions_user.php";

		// If the guy is not in the group yet, add him
		return group_memberships($groupId, $userId, true);
	}

	/**
	 * Adds the given user in the given phpbb group.
	 *
	 * @param int $userId the user ID
	 * @param int $groupId the group ID
	 * @param bool $defaultGroup should it be his default group (true by default)
	 * @return string|bool false if no error occurred, string of I18n from PhpBB in case of error
	 */
	public static function addUserInGroup(
		int $userId,
		int $groupId,
		bool $defaultGroup = true
	) {
		// If the guy is not in the group yet, add him
		return !self::isUserInGroup($groupId, $userId) ?
			// see https://wiki.phpbb.com/Function.group_user_add
			group_user_add($groupId, $userId, false, false, $defaultGroup) :
			false;
	}

	/**
	 * Logs out the connected PhpBB user.
	 */
	public function logout() {
		$this->user->session_kill();
	}

}
