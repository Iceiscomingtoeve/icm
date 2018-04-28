<?php

namespace Controller;

use Model\Bean\OAuth2Users;
use Model\Bean\UserSession;
use Model\Session\EVECharacter;
use Utils\Handler\PhpBB;
use Utils\Utils;
use View\Errors\Error501;
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
	 * @return AController the right AController or NULL if any found
	 */
	public static final function getInstance(string $page) {
		// Retrieves the AController if already created
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		// Retrieve the page + upper case the first letter
		$page = ucfirst($page);
		// Retrieves the Controller which match the page
		$controllerFile = PATH_CONTROLLER . "/" . $page . ".php";
		// If the Controller is found, include it
		if (is_file($controllerFile)) {
			// Update the class name with the namespace of the current one: Controller
			$className = __NAMESPACE__ . "\\" . $page;
			self::$INSTANCE = new $className();
		} else {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Controller " . $controllerFile . " does not exist !", time());
			Utils::redirect("/errors/404");
		}
		self::$INSTANCE->session = UserSession::getSession();
		self::$INSTANCE->getActiveCharacterFromUser();
		return self::$INSTANCE;
	}

	/**
	 * Executes the action and provides the parameters array to treat the needs.
	 *
	 * @param string $action asked action
	 * @param array $params parameters in an array
	 * @return View the view according to the controller
	 */
	public final function executeAction(string $action = self::DEFAULT_ACTION, array $params = array()) {
		if (method_exists($this, $action)) {
			return $this->$action($params);
		} else if ($this instanceof Errors) {
			return $this->show($params);
		}
		return new Error501();
	}

	/**
	 * Default action for any AController
	 *
	 * @param array $params parameters in an array
	 * @return View the view to print
	 */
	public function show(array $params = array()) {
		return new Error501();
	}

	/**
	 * Retrieves current linked characters + prepares the UserSession with it.
	 */
	private function getActiveCharacterFromUser() {
		if (!$this->getPhpbbHandler()->isAnonymous()) {
			$this->charactersOAuth = OAuth2Users::getCharacterFromUserId(
				$this->getPhpbbHandler()->getUser()->data['user_id']
			);
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
