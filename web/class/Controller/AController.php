<?php

namespace Controller;

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
