<?php

namespace View;

/**
 * Class ErrorView
 *
 * @package View
 */
class ErrorView implements View {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	public function showTemplate() {
?>
<p class="erreur">Erreur inconnue !</p>
<?php
	}
}
