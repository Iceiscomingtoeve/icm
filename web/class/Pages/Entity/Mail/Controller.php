<?php

namespace Pages\Entity\Mail;

use Controller\AController;
use EVEOnline\ESI\EsiFactory;
use View\DebugView;
use View\Errors\NoActiveCharacterError;
use View\Errors\NotConnectedForumError;
use View\View;

/**
 * Handles the show action in Index page
 *
 * @package Pages\Entity\Mail
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		}

		if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		$esi = EsiFactory::createEsi($oauthUser);
		//TODO: Handles properly the API lost
		$res = $esi->invoke(
			"get",
			"/characters/{character_id}/mail/",
			array("character_id" => $oauthUser->id_entity)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new DebugView($json);
	}

}
