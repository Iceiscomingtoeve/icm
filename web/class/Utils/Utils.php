<?php

namespace Utils;

/**
 * Utility class
 *
 * @package Utils
 */
final class Utils {

	/**
	 * Retrieves the stacktrace call.
	 *
	 * @param bool $debugInFile Should it save the stack in file ?
	 * @return string the full stacktrace
	 */
	public static final function callStack(bool $debugInFile = true) {
		$message = print_r(debug_backtrace(), true);

		if ($debugInFile) {
			self::log($message);
		}
		return $message;
	}

	/**
	 * Logs the given message in the php error log file
	 *
	 * @param string $message message to log
	 * @param int $time the date put in a prefix if given (see time())
	 */
	public static final function log(string $message, int $time = NULL) {
		$messageDebug = str_repeat("-", 30) . "\r\n";
		if ($time != NULL) {
			$messageDebug .= "[" . self::formatDate($time, true) . "] ";
		}
		$messageDebug .= $message . "\r\n";

		$logPHP = fopen(PATH_LOG_PHP_ERROR, "a");
		fwrite($logPHP, $messageDebug, strlen($messageDebug));
		fclose($logPHP);
	}

	/**
	 * Format given timestamp to print the day, month then year: DD/MM/YYYY.
	 *
	 * @param int $timestamp unix timestamp (if negative given, will take the current time())
	 * @param bool $showHour should it also prints hours and minutes ?
	 * @return string formatted date as a string
	 */
	//TODO: Put in dedicated DateUtils class with 2 format: date and datetime
	public static final function formatDate(
		int $timestamp = -1,
		bool $showHour = false
	): string {
		if (!is_numeric($timestamp) || $timestamp < 0) {
			$timestamp = time();
		}
		return !$showHour ?
			date("d/m/Y", $timestamp) :
			date("d/m/Y H:i:s", $timestamp);
	}

	/**
	 * Date en format JJ/MM/AAAA transformé en timestamp UNIX
	 *
	 * @param string $date La date sous forme JJ/MM/AAAA. Peut être NULL: il prendra le jour d'aujourd'hui
	 * @return int Timestamp UNIX correspondant à la date
	 */
	public static final function dateTimestampUnix(string $date = NULL) {
		// $timestamp == "JJ/MM/AAAA"
		if (strpos($date, "/") !== false) {
			$infos = explode("/", $date);
			$jour = $infos[0];
			$mois = $infos[1];
			$annee = $infos[2];
		} else {
			$date = time();

			$jour = date("d", $date);
			$mois = date("m", $date);
			$annee = date("Y", $date);
		}

		return mktime(0, 0, 0, $mois, $jour, $annee);
	}

	/**
	 * Allows to send simple mail to people.
	 *
	 * @param string $subject the subject of the mail
	 * @param string $message the message of the mail
	 * @param string $mailTo the receiver mail address
	 * @param string $mailFrom the sender mail address
	 * @param string $mailCopy the copied receiver (comma separated)
	 * @param string $mailHiddenCopy the hidden copied receiver (comma separated)
	 * @return bool true if mail was sent, false otherwise
	 * @see mail()
	 */
	public static final function sendMail(
		string $subject,
		string $message,
		string $mailTo,
		string $mailFrom = MAIL_ADMINISTRATOR,
		string $mailCopy = NULL,
		string $mailHiddenCopy = NULL
	) {
		$headers = "MIME-version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: EVEMyAdmin <" . $mailFrom . ">\n";
		if (!is_null($mailCopy)) {
			$headers .= "Cc: " . $mailCopy . "\n";
		}
		if (!is_null($mailHiddenCopy)) {
			$headers .= "Bcc: " . $mailHiddenCopy . "\n";
		}

		return mail($mailTo, $subject, $message, $headers);
	}

	/**
	 * Retrieves the last position of the given character.
	 *
	 * @param string $string the full string
	 * @param string $character the character to find
	 * @return int|bool The position of the character in the string, false if not found
	 */
	public static final function lastIndexOf(string $string, string $character) {
		$pos = strpos(strrev($string), $character);
		if ($pos != false) {
			return (strlen($string) - $pos) - 1;
		}
		return false;
	}

	/**
	 * Redirects the user to the given URI.<br>
	 * Uses header method
	 *
	 * @param string $uri destination URI
	 * @see header()
	 */
	public static final function redirect(string $uri) {
		header("Location: " . $uri);
		session_write_close();
		exit;
	}

	/**
	 * Prints the given word in plural.
	 *
	 * @param int $nb the number
	 * @param string $word the word
	 * @return string the number and the word (with a s if required)
	 */
	public static final function plural(int $nb, string $word) {
		if ($nb > 1) {
			return $nb . " " . str_replace(" ", "s ", $word) . "s";
		}
		return $nb . " " . $word;
	}

	/**
	 * Hashes the given string into SHA512
	 *
	 * @param string $string the string
	 * @param string $salt the salt to apply (if any)
	 * @return string the SHA512 string
	 */
	public static final function sha512(string $string, string $salt = NULL) {
		$hash = !is_null($salt) ?
			self::sha512($salt, NULL) . $string :
			$string;
		return hash("sha512", $hash);
	}

	/**
	 * Calls the htmlentities method for each value of the array
	 *
	 * @param string|array $toEncode string or array to encode.
	 * @return string string encoded
	 * @see htmlentities()
	 * @see html_entity_decode_array_map() to decode
	 */
	public static final function htmlentities_array_map($toEncode) {
		// Loop down the array to encode
		if (is_array($toEncode)) {
			foreach ($toEncode as $subArray) {
				self::htmlentities_array_map($subArray);
			}
		}
		return trim(
			htmlentities($toEncode, ENT_QUOTES, "UTF-8", false),
			" \t\n\r\0\x0B"
		);
	}

	/**
	 * Calls the html_entity_decode method for each value of the array
	 *
	 * @param string|array $toDecode string or array to decode
	 * @return string string decoded
	 */
	public static final function html_entity_decode_array_map($toDecode) {
		// Loop down the array to decode
		if (is_array($toDecode)) {
			foreach ($toDecode as $subArray) {
				self::html_entity_decode_array_map($subArray);
			}
		}
		return html_entity_decode($toDecode, ENT_QUOTES, "UTF-8");
	}

}
