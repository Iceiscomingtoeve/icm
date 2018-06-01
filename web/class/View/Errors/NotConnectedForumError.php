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
<a href="<?= OAUTH_LOGIN_URL ?>">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a><br>
<br>
Vous n'êtes pas connecté sur le <a href="<?= PHPBB_URL; ?>" target="_blank">forum</a>.
<?php
	}
}
