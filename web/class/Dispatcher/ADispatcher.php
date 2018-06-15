<?php

namespace Dispatcher;

use Controller\AController;
use Model\Bean\UserSession;
use Pages\Errors\Views\Error404;
use phpbb\request\request_interface;
use Utils\Handler\ErrorHandler;
use Utils\Handler\PhpBB;
use View\ErrorView;
use View\JsonErrorView;
use View\JsonView;
use View\View;

/**
 * Define a Dispatcher: a way to respond correctly to the user
 *
 * @package Dispatcher
 */
abstract class ADispatcher {

	// Types of dispatcher available
	const DISPATCHER_PHP = "PHP";
	const DISPATCHER_AJAX = "AJAX";
	// Default controller
	const DEFAULT_CONTROLLER = "Index";

	/**
	 * Asked page name
	 * @var string
	 */
	protected $page;

	/**
	 * Current AController
	 * @var \Controller\AController
	 */
	protected $controller;

	/**
	 * Instance of the dispatcher
	 * @var ADispatcher
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * Retrieves the instance of the Dispatcher
	 *
	 * @return ADispatcher instance of the right Dispatcher (PHP, AJAX, other)
	 */
	public static function getInstance() {
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		$request = PhpBB::getInstance()->getRequest();
		$page = self::getPage($request);
		self::$INSTANCE = self::getDispatcherType($page);
		self::$INSTANCE->page = $page;
		self::$INSTANCE->controller = AController::getInstance(
			$page,
			self::getAction($request)
		);
		return self::$INSTANCE;
	}

	/**
	 * Retrieves the required type of Dispatcher according to the
	 * given page name.
	 *
	 * @param string $page name of the page
	 * @return ADispatcher the required dispatcher for the page
	 */
	private static final function getDispatcherType(string $page) {
		// List here every AJAX pages
		$pagesAJAX = array(
			"ajax",
			"cron"
		);

		// If the page is in the array
		if (in_array($page, $pagesAJAX)) {
			return new AJAX();
		}
		return new PHP();
	}

	/**
	 * Retrieves the page from the $_GET.<br>
	 * Also replaces "-" into "_".
	 *
	 * @param \phpbb\request\request $request the phpbb request
	 * @return string the asked page
	 */
	private static final function getPage(\phpbb\request\request $request) {
		$page = $request->variable("page", ADispatcher::DEFAULT_CONTROLLER);
		return str_replace("-", "_", strtolower($page));
	}

	/**
	 * Retrieves the action from the $_GET.<br>
	 * Also replaces "-" into "_".
	 *
	 * @param \phpbb\request\request $request the phpbb request
	 * @return string the asked action
	 */
	public static final function getAction(\phpbb\request\request $request) {
		$action = $request->variable("action", AController::DEFAULT_ACTION);
		return str_replace("-", "_", strtolower($action));
	}

	/**
	 * Dispatches the page, the action, and parameters to the
	 * right AController.
	 *
	 * @return View the view to print
	 */
	public final function dispatch() {
		$request = PhpBB::getInstance()->getRequest();

		if ($this->controller != NULL) {
			// If it's an AJAX Dispatcher, it must be bug-free
			if ($this instanceof AJAX) {
				try {
					$view = $this->controller->execute(self::getParameters($request));
				} catch (\Throwable $ex) {
					ErrorHandler::logException($ex, DEBUG);
					$view = new JsonErrorView($ex->getMessage());
				}
			} else {
				$view = $this->controller->execute(self::getParameters($request));
			}
			$view = $this->handleResponse($view);

			// Sets the current URI (if not an error) in the cookie in case of callback redirection
			if (!($view instanceof ErrorView) &&
				!($view instanceof JsonView)
			) {
				UserSession::getSession()->setActiveUri(
					$request->variable(
						"REQUEST_URI",
						"/",
						true,
						request_interface::SERVER
					)
				);
			}

			return $view;
		}
		return $this->handleResponse(new Error404());
	}

	/**
	 * Merge GET and POST values into one single array.<br>
	 * Also removes value from page and action used by ADispatcher and AController
	 *
	 * @param \phpbb\request\request $request the phpbb request
	 * @return array every values
	 */
	public static final function getParameters(\phpbb\request\request $request) {
		$values = array_merge_recursive(
			array(),
			$request->get_super_global(request_interface::GET),
			$request->get_super_global(request_interface::POST)
		);
		if (array_key_exists("page", $values) && !empty($values['page'])) {
			unset($values['page']);
		}
		if (array_key_exists("action", $values) && !empty($values['action'])) {
			unset($values['action']);
		}

		$getParams = $request->variable("params", AController::DEFAULT_ACTION);
		if (is_string($getParams)) {
			unset($values['params']);
			foreach (explode("/", $getParams) as $param) {
				if (empty($param)) {
					continue;
				}
				$values[] = $param;
			}
		}
		return $values;
	}

	/**
	 * Handles the response accordingly.
	 *
	 * @param View $view the view
	 * @return View the View to print
	 */
	protected abstract function handleResponse(View $view);

}
