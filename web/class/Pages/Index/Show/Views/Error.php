<?php

namespace Pages\Index\Show\Views;

use View\ErrorView;

/**
 * Class Error for the show in Index controller
 *
 * @package Pages\Index\Show\Views
 */
class Error extends ErrorView {

	public function showTemplate() {
?>
Vous n'êtes pas connecté sur le <a href="<?= PHPBB_URL; ?>" target="_blank">forum</a>.
<?php
	}
}
