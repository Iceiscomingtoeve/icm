<?php

namespace Utils\Handler;

/**
 * Handles the link with PhpBB.
 *
 * @package Utils\Handler
 */
class PhpBB implements Handler {

	private static $INSTANCE = NULL;

	/**
	 * @var \phpbb\user $user the phpbb user
	 */
	private $user;

	/**
	 * @var \phpbb\request\request $request the way to retrieve value from GET and POST from PhpBB
	 */
	private $request;

	public static function getInstance() {
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}
		self::$INSTANCE = new PhpBB();
		return self::$INSTANCE;
	}

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
	 * @return bool true if the user is a real one, false if he is not logged
	 */
	public function isNotAnonymous() {
		//ANONYMOUS is a define on phpbb side which is equals to 1
		return $this->user->data['user_id'] != ANONYMOUS;
	}

	/**
	 * Logs out the connected PhpBB user.
	 */
	public function logout() {
		$this->user->session_kill();
	}

}