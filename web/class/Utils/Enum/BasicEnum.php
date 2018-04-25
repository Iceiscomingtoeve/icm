<?php

namespace Utils\Enum;

use Utils\Handler\ErrorHandler;

/**
 * Abstract class for any enum.
 *
 * @package Utils\Enum
 * @see http://octocado.com/?p=596
 */
abstract class BasicEnum {

	/** @var string $value the name of the enum */
	private $value;

	/** @var array $constCacheArray cache array of constant */
	private $constCacheArray = array();

	public final function __construct(string $value) {
		$value = strtoupper($value);
		$constants = $this->getConstants();
		if(!array_key_exists($value, $constants)) {
			throw new UnexpectedValueException("Undefined constant: " . $value);
		}
		$this->value = $value;
	}

	final public function __toString() {
		return $this->value;
	}

	private function getConstants() {
		if (empty($this->constCacheArray)) {
			try {
				$reflect = new \ReflectionClass($this);
				$this->constCacheArray = array_map("strtoupper", $reflect->getConstants());
			} catch (\ReflectionException $ex) {
				ErrorHandler::logException($ex);
			}
		}
		return $this->constCacheArray;
	}

	public function __debugInfo() {
		return array(
			"value" => $this->value
		);
	}

}
