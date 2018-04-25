<?php

namespace Utils\Enum;

/**
 * Called when the request value from a BasicEnum is not in the constant list.
 *
 * @package Utils\Enum
 */
class UnexpectedValueException extends \RuntimeException {

	/** @var string $message the message of the error */
	protected $message;

	/**
	 * UnexpectedValueException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		$this->message = $message;
	}

}