<?php

namespace Utils;

/**
 * Treatment utility class handler
 */
final class TreatmentUtils {

	/**
	 * When did the treatment started.
	 * @var int the start unix timestamp
	 */
	private $start;

	/**
	 * TreatmentUtils constructor.
	 */
	public function __construct() {
		$this->start = time();
	}

	/**
	 * Retrieves the current treatment time (in µs)
	 *
	 * @return int the treatment time (rounded with a 5 digit precision) (in µs)
	 */
	public final function getTreatmentTime() {
		return round(microtime(true) - $this->start, 5);
	}

}