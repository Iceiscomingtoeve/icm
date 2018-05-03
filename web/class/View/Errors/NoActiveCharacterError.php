<?php

namespace View\Errors;

use View\ErrorView;

/**
 * Global Error view if the user didn't choose a character
 *
 * @package View\Errors
 */
class NoActiveCharacterError extends ErrorView {

	public function showTemplate() {
?>
Vous n'avez pas sélectionné de personnage.
<?php
	}
}
