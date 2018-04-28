<?php

namespace View;

/**
 * Interface View
 *
 * @package View
 */
interface View {

	/**
	 * Retrieves the page title.
	 *
	 * @return string the current page to be printed
	 */
	public function getPageTitle();

	/**
	 * Retrieves the current breadcrumb (or empty if not required).
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb();

	/**
	 * Retrieves the HTML to print on the page
	 *
	 * @return string the HTML stuff to give to user
	 */
	public function showTemplate();

}
