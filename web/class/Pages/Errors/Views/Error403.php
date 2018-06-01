<?php

namespace Pages\Errors\Views;

use View\ErrorView;

/**
 * Class Error403 for the show in Errors controller
 *
 * @package Pages\Errors\Views
 */
class Error403 extends ErrorView {

	public function showTemplate() {
?>
<p class="erreur">Accès refusé !</p>
<?php
	}

}
