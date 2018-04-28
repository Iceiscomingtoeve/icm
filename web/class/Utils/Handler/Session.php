<?php

namespace Utils\Handler;

/**
 * Session handler. Avoid $_SESSION call.
 *
 * @package Utils\Handler
 */
final class Session implements Handler {

	/**
	 * Sets the given value on the given (variable) name.
	 *
	 * @param string $name name of your variable
	 * @param mixed $value value of your variable
	 */
	public static final function setSession(string $name, $value) {
		$_SESSION[$name] = $value;
	}

	/**
	 * Retrieves the variable value given.
	 *
	 * @param string $name name of your variable
	 * @return mixed|NULL the value if found or NULL
	 */
	public static final function getSession(string $name) {
		return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : NULL;
	}

	/**
	 * Deletes the given variable from the session.
	 *
	 * @param string $name name of your variable. If NULL given, will clean out the session.
	 * @return int returns the number of variable cleaned (must be greater than 0)
	 */
	public static final function deleteSession(string $name) {
		$nbSession = 0;

		if (is_null($name)) {
			// Retrieves all variables names
			$availableKeys = array_keys($_SESSION);
			// Removes every keys
			foreach ($availableKeys as $key) {
				$nbSession += self::setSession($key, NULL);
			}
		} else if (array_key_exists($name, $_SESSION)){
			$nbSession++;
			unset($_SESSION[$name]);
		}

		return $nbSession;
	}

}
