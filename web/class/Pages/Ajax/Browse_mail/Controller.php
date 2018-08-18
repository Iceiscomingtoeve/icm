<?php

namespace Pages\Ajax\Browse_mail;

use Controller\AController;
use EVEOnline\ESI\EsiFactory;
use View\JsonView;
use View\View;

/**
 * Handles browsing previous character's mails
 *
 * @package Pages\Ajax\Browse_mail
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			http_response_code(401);
			die("Unauthorized");
		} else if (is_null($this->session->getActiveCharacter())) {
			http_response_code(405);
			die("Method Not Allowed");
		} else if (empty($params) || !is_numeric($params[0])) {
			http_response_code(409);
			die("Missing parameter");
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		$esi = EsiFactory::createEsi($oauthUser);
		//TODO: Handles properly the API lost
		$res = $res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/mail/{mail_id}/",
			array(
				"character_id" => $oauthUser->id_entity,
				"mail_id" => intval($params[0])
			)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new JsonView($json);
	}

}
