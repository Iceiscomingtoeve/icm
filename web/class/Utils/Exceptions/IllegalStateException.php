<?php

namespace Utils\Exceptions;

/**
 * Called when the state/status is not permitted.
 *
 * @package Utils\Enum
 */
class IllegalStateException extends \RuntimeException {

	/**
	 * IllegalStateException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		parent::__construct($message);
	}

}
