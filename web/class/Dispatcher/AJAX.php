<?php

namespace Dispatcher;

use Pages\Errors\Views\Error404;
use Utils\Handler\ErrorHandler;
use Utils\Utils;
use View\JsonErrorView;
use View\View;

/**
 * Dispatcher for the AJAX
 */
final class AJAX extends ADispatcher {

	protected final function handleResponse(
		\phpbb\request\request $request
	): View {
		try {
			$view = $this->controller->execute(self::getParameters($request));
		} catch (\Throwable $ex) {
			ErrorHandler::logException($ex, DEBUG);
			$view = new JsonErrorView($ex->getMessage());
		}
		$this->prepareJsonResponse($view);

		// Nothing more required, die "properly"
		die;
	}

	private function prepareJsonResponse(View $view) {
		$json = array();

		// If the result is OK
		if (!($view instanceof Error404)) {
			// Removes the first value in the array
			$values = $view->showTemplate();

			// If the controller had an error
			if ($view instanceof JsonErrorView) {
				$json['state'] = "error";
				$json['error'] = $values;
			} else {
				$json['state'] = "ok";
				$json['value'] = $values;
			}
		} else {
			$json['state'] = "error";
			$json['error'] = "Controller does not exist.";
		}
		// Modify HTML into UTF-8 elements
		array_walk_recursive($json, Utils::class . "::htmlentities_array_map");

		// Prints the JSON
		echo json_encode($json, JSON_UNESCAPED_SLASHES);
	}

}
