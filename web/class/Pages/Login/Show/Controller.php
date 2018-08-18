<?php

namespace Pages\Login\Show;

use Controller\AController;
use Pages\Login\Show\Views\Success;
use View\View;

/**
 * Handles the show action in Index page
 *
 * @package Pages\Index\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		return new Success();
	}

}
