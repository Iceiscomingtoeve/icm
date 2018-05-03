<?php

namespace Pages\Index\Show\Views;

use EVEOnline\ESI\Character\CharacterDetails;
use Utils\Builder\BreadcrumbBuilder;
use View\View;

/**
 * Class Success for the show in Index controller
 *
 * @package Pages\Index\Show\Views
 */
class Success implements View {

	/** @var array $characters  */
	private $characters;

	/**
	 * UpdatePhpbb constructor.
	 *
	 * @param CharacterDetails[] $characters
	 */
	public function __construct(
		array $characters
	) {
		$this->characters = $characters;
	}

	public function getPageTitle() {
		return "Players";
	}

	public function getBreadcrumb() {
		return BreadcrumbBuilder::createBreadcrumb();
	}

	public function showTemplate() {
?>
<a href="<?= OAUTH_LOGIN_URL ?>">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<br>
<br>
Vous avez synchornisé <?= \Utils\Utils::plural(count($this->characters), "personnage"); ?>:<br>
<?php foreach ($this->characters as $character) : ?>
	<?php $urlPortrait = IMAGE_SERVER_URL . "/Character/" . $character->getCharacterId() . "_128.jpg"; ?>
	<img src="<?= $urlPortrait; ?>" alt="Portrait" title="<?= $character->getName(); ?>" class="rounded-circle">
<?php endforeach; ?>
<?php
	}
}
