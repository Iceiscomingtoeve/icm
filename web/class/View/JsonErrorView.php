<?php

namespace View;

/**
 * Class JsonErrorView
 *
 * @package View
 */
class JsonErrorView extends JsonView {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	/**
	 * JsonErrorView constructor.
	 *
	 * @param mixed $status the value to print (string ,array, int, whatever)
	 */
	public function __construct($status) {
		parent::__construct($status);
	}

}
