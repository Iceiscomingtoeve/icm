<?php

namespace Utils\Handler;

use phpbb\request\request_interface;

/**
 * Cookie handler. Avoid $_COOKIE call.
 *
 * @package Utils\Handler
 */
final class Cookie implements Handler {

	/**
	 * Checks if the cookie were accepted by the user
	 *
	 * @return bool true if the user accepted it, false if he must
	 */
	public static final function wasCookieUsageWarned() {
		return strstr(self::getCookie(DISCLAIMER_NAME), "true") !== false;
	}

	/**
	 * Sets the given value of your variable in the cookie of the user.
	 *
	 * @param string $name name of your variable in the cookie
	 * @param mixed $value value of your variable in the cookie
	 * @param int $expire set the expiration time (-1 to remove)
	 */
	public static final function setCookie(
		string $name,
		$value,
		int $expire = COOKIE_DEFAULT_EXPIRATION
	) {
		setcookie(
			$name,
			$value,
			time() + (is_numeric($expire) ? $expire : COOKIE_DEFAULT_EXPIRATION),
			"/",
			DOMAIN,
			true,
			true
		);
	}

	/**
	 * Retrieves the variable value given.
	 *
	 * @param string $name name of your variable
	 * @return mixed|NULL the value if found or NULL
	 */
	public static final function getCookie(string $name) {
		$request = PhpBB::getInstance()->getRequest();
		return $request->variable($name, NULL, true, request_interface::COOKIE);
	}

	/**
	 * Deletes the given variable from the cookie.
	 *
	 * @param string $name name of your variable. If NULL given, will clean out the cookie.
	 * @return int returns the number of variable cleaned (must be greater than 0)
	 */
	public static final function deleteCookie(string $name) {
		$nbCookie = 0;
		$cookie = PhpBB::getInstance()->getRequest()->get_super_global(request_interface::COOKIE);

		if (is_null($name)) {
			// Retrieves all variables names
			$availableKeys = array_keys($cookie);
			// Removes every keys
			foreach ($availableKeys as $key) {
				$nbCookie++;
				self::setCookie($key, NULL, -1);
			}
		} else if (array_key_exists($name, $cookie)){
			$nbCookie++;
			self::setCookie($name, NULL, -1);
		}

		return $nbCookie;
	}

}
