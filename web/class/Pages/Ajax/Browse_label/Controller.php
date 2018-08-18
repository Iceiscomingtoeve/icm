<?php

namespace Pages\Ajax\Browse_label;

use Controller\AController;
use EVEOnline\ESI\EsiFactory;
use EVEOnline\ESI\Utils\EntitiesRetriever;
use EVEOnline\ESI\Utils\Enums\EntityType;
use EVEOnline\ESI\Utils\SimpleEntityInfo;
use View\JsonView;
use View\View;

/**
 * Handles browsing character's mail label
 *
 * @package Pages\Ajax\Browse_label
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
		$res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/mail/",
			array("character_id" => $oauthUser->id_entity),
			array("labels" => array(intval($params[0])))
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		$entities = array();
		foreach ($json as $mail) {
			$entities[$mail['from']] = new SimpleEntityInfo(
				$mail['from'],
				"",
				new EntityType(EntityType::CHARACTER)
			);
		}

		// Injects the character name in the returned JSON
		EntitiesRetriever::getEntityInfo($entities);
		foreach ($json as &$mail) {
			$mail['from_name'] = $entities[$mail['from']]->getName();
		}

		return new JsonView($json);
	}

}
