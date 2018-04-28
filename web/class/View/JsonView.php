<?php

namespace View;

/**
 * Class JsonView
 *
 * @package View
 */
class JsonView implements View {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	private $status;

	/**
	 * JsonView constructor.
	 *
	 * @param mixed $status the value to print (string ,array, int, whatever)
	 */
	public function __construct($status) {
		$this->status = $status;
	}

	public function showTemplate() {
		return $this->status;
	}

}
