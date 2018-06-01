<?php

namespace View;
use Utils\Builder\BreadcrumbBuilder;

/**
 * Returns the default Breadcrumb.
 *
 * @package View
 */
trait DefaultBreadcrumb {

	/**
	 * Retrieves the current breadcrumb.
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb() {
		return BreadcrumbBuilder::createBreadcrumb();
	}

}
