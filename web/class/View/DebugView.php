<?php

namespace View;

use Utils\Builder\BreadcrumbBuilder;

/**
 * Class Success for the show in Index controller
 *
 * @package Pages\Index\Show\Views
 */
class DebugView implements View {

	use EmptyPageTitle;

	/** @var mixed $values  */
	private $values;

	public function __construct(
		$values
	) {
		$this->values = $values;
	}

	public function getBreadcrumb() {
		return BreadcrumbBuilder::createBreadcrumb();
	}

	public function showTemplate() {
		debug($this->values);
	}

}
