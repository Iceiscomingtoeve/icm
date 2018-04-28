<?php

namespace Controller;

use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\Character\CharacterRoles;
use EVEOnline\ESI\EsiFactory;
use Model\Bean\OAuth2Users;
use Seat\Eseye\Exceptions\RequestFailedException;
use Utils\Handler\PhpBB;
use Utils\Utils;
use View\JsonView;

/**
 * Handles the Cron page
 */
final class Cron extends AController {

	public function show(array $params = array()) {
		Utils::redirect("/");
	}

	public function update_phpbb_groups(array $params = array()) {
		$characters = OAuth2Users::getAllCharacters();

		$updateStatus = array();
		foreach ($characters as $characterOAuth) {
			$esi = EsiFactory::createEsi($characterOAuth);
			try {
				$res = $esi->invoke(
					"get",
					"/characters/{character_id}/",
					array("character_id" => $characterOAuth->id_character)
				);

				// Retrieve the raw JSON of the current player
				$json = json_decode($res->raw, true);
				$character = CharacterDetails::create(
					$characterOAuth->id_character,
					$json
				);
				// If the character is in the right corporation
				if ($character->getCorporationId() == CORPORATION_ID) {
					$inCorp = PhpBB::addUserInGroup($characterOAuth->id_forum_user, PHPBB_GROUP_VERIFIED_ID);
					$updateStatus[$character->getCharacterId()]['in_corp'] = $inCorp === false ? "yes" : "FAIL: " . $inCorp;
				} else {
					$updateStatus[$character->getCharacterId()]['in_corp'] = "no";
				}

				$res = $esi->invoke(
					"get",
					"/characters/{character_id}/roles/",
					array("character_id" => $characterOAuth->id_character)
				);
				// Retrieve the raw JSON
				$json = json_decode($res->raw, true);
				$roles = CharacterRoles::create($json);
				if (in_array("Director", $roles->getRoles())) {
					$isDirector = PhpBB::addUserInGroup($characterOAuth->id_forum_user, PHPBB_GROUP_DIRECTOR_ID);
					$updateStatus[$character->getCharacterId()]['is_director'] = $isDirector === false ? "yes" : "FAIL: " . $isDirector;
				} else {
					$updateStatus[$character->getCharacterId()]['is_director'] = "no";
				}
			} catch (RequestFailedException $ex) {
				;
			}
		}
		return new JsonView($updateStatus);
	}

}
