<?php

namespace Controller;

use Model\Bean\OAuth2Users;
use Model\Bean\UserSession;
use Model\Session\EVECharacter;
use Utils\Handler\PhpBB;
use Utils\Utils;
use View\View;

/**
 * Handles every Controller from this abstract class.
 */
abstract class AController {

	// Default action on every controller
	const DEFAULT_ACTION = "show";

	/**
	 * Current instance of AController
	 * @var AController
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * @var UserSession $session the current User Session
	 */
	protected $session;

	/**
	 * @var OAuth2Users[] $charactersOAuth linked characters
	 */
	protected $charactersOAuth = array();

	/**
	 * Retrieves the AController that can handle the asked page.
	 *
	 * @param string $page page name
	 * @param string $action action (show by default)
	 * @return AController the right AController or NULL if any found
	 */
	public static final function getInstance(
		string $page,
		string $action = self::DEFAULT_ACTION
	) {
		// Retrieves the AController if already created
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		// Retrieve the page + upper case the first letter
		$page = ucfirst($page);
		$action = strcmp("Errors", $page) == 0 ? "All" : ucfirst($action);

		// Retrieves the Controller which match the page and action
		$controllerPath = "Pages" .
			DIRECTORY_SEPARATOR .
			ucfirst($page) .
			DIRECTORY_SEPARATOR .
			ucfirst($action) .
			DIRECTORY_SEPARATOR .
			"Controller.php";

		// If the Controller is found, include it
		if (is_file(PATH_CLASS . DIRECTORY_SEPARATOR . $controllerPath)) {
			// Update the class name with the namespace and remove the ".php" extension
			$className = str_replace(
				DIRECTORY_SEPARATOR,
				"\\",
				substr($controllerPath, 0, Utils::lastIndexOf($controllerPath, "."))
			);
			self::$INSTANCE = new $className();
		} else {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Controller " . PATH_CLASS . "/" . $controllerPath . " does not exist !", time());
			Utils::redirect("/errors/404");
		}
		self::$INSTANCE->session = UserSession::getSession();
		self::$INSTANCE->getActiveCharacterFromUser();
		return self::$INSTANCE;
	}

	/**
	 * Default action for any AController
	 *
	 * @param array $params parameters in an array
	 * @return View the view to print
	 */
	public abstract function execute(array $params = array()): View;

	/**
	 * Retrieves current linked characters + prepares the UserSession with it.
	 */
	private function getActiveCharacterFromUser() {
		$currentUserId = $this->getPhpbbHandler()->getUser()->data['user_id'];
		if (!$this->getPhpbbHandler()->isAnonymous()) {
			$this->charactersOAuth = OAuth2Users::getCharacterFromUserId($currentUserId);
		}

		// Security check to not allow user to see other player's character
		if (!$this->getPhpbbHandler()->isDirector() &&
			!is_null($this->session->getActiveCharacter()) &&
			$this->session->getActiveCharacter()->getOauthUser()->id_forum_user != $currentUserId
		) {
			$this->session->setActiveCharacter(NULL);
		}

		foreach ($this->charactersOAuth as $character) {
			$eveCharacter = new EVECharacter($character);
			if ($character->is_main_character && is_null($this->session->getActiveCharacter())) {
				$this->session->setActiveCharacter($eveCharacter);
			}
			$this->session->addEVECharacter($eveCharacter);
		}
	}

	/**
	 * @return PhpBB the PhpBB Handler
	 */
	protected function getPhpbbHandler() {
		return PhpBB::getInstance();
	}

	/**
	 * Prints the current instance of AController
	 *
	 * @return string the current AController class name
	 */
	public final function __toString() {
		return get_class(self::$INSTANCE);
	}

}
