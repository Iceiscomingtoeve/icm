<?php

namespace Pages\Errors\All;

use Controller\AController;
use Pages\Errors\Views\Error403;
use Pages\Errors\Views\Error404;
use Pages\Errors\Views\Error501;
use phpbb\request\request_interface;
use View\ErrorView;
use View\View;

/**
 * Handles the Errors page
 *
 * @package Pages\Errors\All
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
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
