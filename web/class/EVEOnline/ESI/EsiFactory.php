<?php

namespace EVEOnline\ESI;

use Model\Bean\OAuth2Users;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Containers\EsiResponse;
use Seat\Eseye\Eseye;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;
use Seat\Eseye\Exceptions\UriDataMissingException;
use Utils\Exceptions\IllegalStateException;
use Utils\Handler\ErrorHandler;
use Utils\Utils;

/**
 * Main class which handles HTTP query, login, and other stuff.
 *
 * @package EVEOnline\ESI
 */
class EsiFactory {

	/** @var string User-Agent in HTTP header to give contact in case of error for EVE developers */
	private static $USER_AGENT = "User-Agent: EVEMyAdmin (Beta). Contact Freyers In-Game";

	/** @var Configuration the ESI configuration (user agent, etc) */
	private static $CONFIGURATION = null;

	/** @var EsiAuthentication[] the ESI authentication by character id */
	private static $AUTHENTICATIONS = array();

	/** @var Eseye[] the ESI handler by character id */
	private static $ESEYES = array();

	/**
	 * Creates an Eseye connection.
	 *
	 * @param OAuth2Users $oauthUser the OAuth2Users user
	 * @return Eseye the ESI connection
	 * @see invoke to call to request through the ESI
	 */
	public static function createEsi(
		OAuth2Users $oauthUser
	): Eseye {
		if (is_null($oauthUser)) {
			throw new \InvalidArgumentException("You must provide a character.");
		}

		// Prepares the configuration of ESI
		if (is_null(self::$CONFIGURATION)) {
			try {
				self::$CONFIGURATION = Configuration::getInstance();
				self::$CONFIGURATION->http_user_agent = self::$USER_AGENT;
				self::$CONFIGURATION->logfile_location = PATH_ESI_LOG;
				self::$CONFIGURATION->file_cache_location = PATH_ESI_CACHE;
			} catch (InvalidContainerDataException $ex) {
				// This exception is thrown if the key of the array do not exist
				// If so, we need to update this and every page is broken until update
				ErrorHandler::logException($ex);
				die;
			}
		}

		// Creates an authentication for ESI if not done yet
		$idCharacter = $oauthUser->id_entity;
		if (!array_key_exists($idCharacter, self::$AUTHENTICATIONS)) {
			try {
				self::$AUTHENTICATIONS[$idCharacter] = new EsiAuthentication(
					array(
						"client_id" => ESI_CLIENT_ID,
						"secret" => ESI_SECRET_KEY,
						"refresh_token" => $oauthUser->refresh_token
					)
				);
			} catch (InvalidContainerDataException $ex) {
				// This exception is thrown if the key of the array do not exist
				// If so, we need to update this and every page is broken until update
				ErrorHandler::logException($ex);
				die;
			}
		}

		if (!array_key_exists($idCharacter, self::$ESEYES)) {
			try {
				self::$ESEYES[$idCharacter] = new Eseye(self::$AUTHENTICATIONS[$idCharacter]);
			} catch (InvalidContainerDataException $ex) {
				// This exception is thrown if the key of the array do not exist
				// If so, we need to update this and every page is broken until update
				ErrorHandler::logException($ex);
				die;
			}
		}
		// Creates the connection
		return self::$ESEYES[$idCharacter];
	}


	/**
	 * Creates an Eseye connection.
	 *
	 * @param OAuth2Users $oauthUser the OAuth2Users user
	 * @param string $method HTTP method of the API (GET, POST, DELETE, PUT, ...)
	 * @param string $url URL of the endpoint
	 * @param array $parameters Parameters required on the URL
	 * @param array $queryHeader Addition element in the header request
	 * @param array $queryBody Addition element in the body request
	 * @return EsiResponse the EsiResponse
	 */
	public static function invoke(
		OAuth2Users $oauthUser,
		string $method,
		string $url,
		array $parameters = array(),
		array $queryHeader = array(),
		array $queryBody = array()
	): EsiResponse {
		$esi = self::createEsi($oauthUser);
		if (!empty($queryHeader)) {
			$esi->setQueryString($queryHeader);
		}
		if (!empty($queryBody)) {
			$esi->setBody($queryBody);
		}

		$params = $parameters;
		//TODO: Also prepare the array for corporation and alliance entity
		$params['character_id'] = $oauthUser->id_entity;

		try {
			return $esi->invoke(strtolower($method), $url, $params);
		} catch (EsiScopeAccessDeniedException $ex) {
			// The scope of the user is not enough, ask him to login again
			Utils::redirect("/login");
		} catch (InvalidContainerDataException $ex) {
			// Dev is a dumbass and ask to set value which do not exists
			ErrorHandler::logException($ex, DEBUG);
		} catch (UriDataMissingException $ex) {
			// Dev is a dumbass and forget to provide mandatory value
			ErrorHandler::logException($ex, DEBUG);
		} catch (RequestFailedException $ex) {
			// ESI servers are down (CCP...)
			Utils::redirect("/errors");
		}
		throw new IllegalStateException("No response given by ESI...?!");
	}

}
