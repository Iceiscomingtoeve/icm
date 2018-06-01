<?php

namespace Utils\Exceptions;

/**
 * Called when the access should not be permitted.
 *
 * @package Utils\Enum
 */
class IllegalAccessException extends \RuntimeException {

	/**
	 * IllegalAccessException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		parent::__construct($message);
	}

}
