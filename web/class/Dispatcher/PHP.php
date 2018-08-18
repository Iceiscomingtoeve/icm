<?php

namespace Dispatcher;

use View\View;

/**
 * Dispatcher for the PHP
 */
final class PHP extends ADispatcher {

	protected final function handleResponse(
		\phpbb\request\request $request
	): View {
		return $this->controller->execute(self::getParameters($request));
	}

}
