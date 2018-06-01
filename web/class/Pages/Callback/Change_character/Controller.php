<?php

namespace Pages\Callback\Change_character;

use Controller\AController;
use Model\Bean\OAuth2Users;
use Model\Session\EVECharacter;
use Utils\Utils;
use View\View;

/**
 * Handles the change character on the Callback page
 *
 * @package Pages\Callback\Change_character
 */
final class Controller extends AController {

	/**
	 * Called when the user wants to change his current character
	 *
	 * @param array $params
	 * @return View the view, will always be null
	 */
	public function execute(array $params = array()): View {
		// The code was not given, might be a manual call to this page.
		if (empty($params)) {
			Utils::redirect("/");
		}

		$characterId = reset($params);
		if (!is_numeric($characterId)) {
			Utils::redirect("/");
		}

		$oauthUser = OAuth2Users::getCharacterFromCharacterId($characterId);
		if (is_null($oauthUser)) {
			Utils::redirect("/");
		}

		if ($this->getPhpbbHandler()->isDirector() ||
			$oauthUser->id_forum_user == $this->getPhpbbHandler()->getUser()->data['user_id']
		) {
			$this->session->setActiveCharacter(new EVECharacter($oauthUser));
			Utils::redirect($this->session->getActiveUri());
		}
		Utils::redirect("/");

		return NULL;
	}

}
