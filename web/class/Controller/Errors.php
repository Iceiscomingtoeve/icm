<?php

namespace Controller;

use phpbb\request\request_interface;
use View\Errors\Error403;
use View\Errors\Error404;
use View\Errors\Error501;
use View\ErrorView;

/**
 * Handles the Errors page
 */
final class Errors extends AController {

	public function show(array $params = array()) {
		$action = $this->getPhpbbHandler()->getRequest()->variable(
			"action",
			"403",
			true,
			request_interface::GET
		);
		if (strcmp($action, "403") == 0) {
			return new Error403();
		} else if (strcmp($action, "404") == 0) {
			return new Error404();
		} else if (strcmp($action, "501") == 0) {
			return new Error501();
		}
		return new ErrorView();
	}

}
