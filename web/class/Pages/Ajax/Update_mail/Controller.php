<?php

namespace Pages\Ajax\Update_mail;

use Controller\AController;
use EVEOnline\ESI\EsiFactory;
use View\JsonView;
use View\View;

/**
 * Handles updating character's mail
 *
 * @package Pages\Ajax\Update_mail
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			http_response_code(401);
			die("Unauthorized");
		} else if (is_null($this->session->getActiveCharacter())) {
			http_response_code(405);
			die("Method Not Allowed");
		} else if (empty($params) ||
			!array_key_exists("read", $params) ||
			!array_key_exists("mails", $params)
		) {
			http_response_code(409);
			die("Missing parameter");
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		foreach (explode(",", $params['mails']) as $mail) {
			EsiFactory::invoke(
				$oauthUser,
				"put",
				"/characters/{character_id}/mail/{mail_id}/",
				array(
					"character_id" => $oauthUser->id_entity,
					"mail_id" => intval($mail)
				),
				array(),
				// Forced to re-set the body each times
				array(
					//"labels" => $params['labels'],
					"read" => $params['read']
				)
			);
		}

		return new JsonView("done");
	}

}
