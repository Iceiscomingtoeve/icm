<?php

namespace Pages\Errors\Views;

use View\ErrorView;

/**
 * Class Error404 for the show in Errors controller
 *
 * @package Pages\Errors\Views
 */
class Error404 extends ErrorView {

	public function showTemplate() {
?>
<p class="erreur">La ressource demandée n'existe pas.</p>
<?php
	}

}
