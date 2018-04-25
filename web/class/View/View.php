<?php

namespace View;

/**
 * Interface View
 *
 * @package View
 */
interface View {

	/**
	 * Prints the HTML result.
	 *
	 * @return string the HTML stuff to give to user
	 */
	public function showTemplate();

}
