<?php

namespace Dispatcher;

use View\View;

/**
 * Dispatcher for the PHP
 */
final class PHP extends ADispatcher {

	protected final function handleResponse(View $view) {
		return $view;
	}

}
