<?php

namespace Pages\Errors\Views;

use View\ErrorView;

/**
 * Class Error501 for the show in Errors controller
 *
 * @package Pages\Errors\Views
 */
class Error501 extends ErrorView {

	public function showTemplate() {
?>
<p class="erreur">L'action demandée n'est pas encore implentée.</p>
<?php
	}

}
