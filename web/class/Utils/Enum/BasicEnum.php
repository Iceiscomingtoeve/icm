<?php

namespace Utils\Enum;

use Utils\Exceptions\UnexpectedValueException;
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

	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}

	/**
	 * Is the given BasicEnum is the same of the current one ?
	 *
	 * @param null|BasicEnum $enum the BasicEnum to test
	 * @return bool true if it's the same, false otherwise
	 */
	public final function equals(?BasicEnum $enum): bool {
		return !is_null($enum) && strcmp($this->value, $enum->value) === 0;
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

	public final function __toString() {
		return $this->getValue();
	}

	public final function __debugInfo() {
		return array(
			"value" => $this->value
		);
	}

}
