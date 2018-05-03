<?php

namespace View\Errors;

use View\ErrorView;

/**
 * Global Error view if the user is not connected to the forum.
 *
 * @package View\Errors
 */
class NotConnectedForumError extends ErrorView {

	public function showTemplate() {
?>
Vous n'êtes pas connecté sur le <a href="<?= PHPBB_URL; ?>" target="_blank">forum</a>.
<?php
	}
}
