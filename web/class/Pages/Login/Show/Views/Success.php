<?php

namespace Pages\Login\Show\Views;

use View\DefaultBreadcrumb;
use View\View;

/**
 * Class Success for the show in Index controller
 *
 * @package Pages\Index\Show\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	public function getPageTitle() {
		return "Login";
	}

	public function showTemplate() {
?>
		Pour profiter pleinement d'<abbr title="EVEMyAdmin">EMA</abbr>, merci de synchroniser votre personnage.<br>
<a href="<?= OAUTH_LOGIN_URL ?>">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<?php
	}
}
