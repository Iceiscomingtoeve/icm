<?php

namespace Pages\Icm\Show;

use Controller\AController;
use Model\Bean\OAuth2Users;
use Model\Bean\PhpBBUsers;
use Pages\Icm\Show\Views\Success;
use Utils\Utils;
use View\View;

/**
 * Handles the show action in Index page
 *
 * @package Pages\Index\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if (!$this->getPhpbbHandler()->isDirector()) {
			Utils::redirect("/");
		}

		$phpbbUsers = PhpBBUsers::getAllCharacters();
		foreach ($phpbbUsers as &$phpbbUser) {
			$phpbbUser->characters = OAuth2Users::getCharacterFromUserId($phpbbUser->user_id);
		}
		return new Success($phpbbUsers);
	}

}
