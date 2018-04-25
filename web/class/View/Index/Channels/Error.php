<?php

namespace View\Index\Channels;

use View\ErrorView;

/**
 * Class Error for the show in Index controller
 *
 * @package View\Index\Show
 */
class Error extends ErrorView {

	private $message;

	/**
	 * Error constructor.
	 * @param string $message
	 */
	public function __construct(string $message) {
		$this->message = $message;
	}

	public function showTemplate() {
		echo $this->message;
	}
}
