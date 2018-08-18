<?php

namespace View\Errors;

use View\ErrorView;

/**
 * Global Error view if the user didn't send every mandatory fields
 *
 * @package View\Errors
 */
class MissingInformation extends ErrorView {

	public function showTemplate() {
?>
Il manque des informations pour pouvoir traiter cette action.
<?php
	}
}
