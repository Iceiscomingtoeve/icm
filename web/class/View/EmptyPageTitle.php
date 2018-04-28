<?php

namespace View;

/**
 * Returns an empty page's title.
 *
 * @package View
 */
trait EmptyPageTitle {

	/**
	 * Retrieves the page title.
	 *
	 * @return string the current page to be printed
	 */
	public function getPageTitle() {
		return "";
	}

}
