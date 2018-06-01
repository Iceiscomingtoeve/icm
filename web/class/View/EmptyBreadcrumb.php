<?php

namespace View;

/**
 * Returns an empty Breadcrumb.
 *
 * @package View
 */
trait EmptyBreadcrumb {

	/**
	 * Retrieves the current breadcrumb (or empty if not required).
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb() {
		$breadcrumb = "<ol class=\"breadcrumb float-right\">";
/*
			<li class="breadcrumb-item"><a href="#">Minton</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
*/
		return $breadcrumb . "</ol>";
	}

}
