<?php

namespace Pages\Cron\Update_phpbb_groups;

use Controller\AController;
use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\Character\CharacterRoles;
use EVEOnline\ESI\EsiFactory;
use Model\Bean\OAuth2Users;
use Seat\Eseye\Exceptions\RequestFailedException;
use Utils\Handler\PhpBB;
use View\JsonView;

/**
 * Handles the Controller page
 *
 * @package Pages\Cron\Update_phpbb_groups
 */
final class Controller extends AController {

	public function execute(array $params = array()) {
		// Mapping EVE Character to PhpBB user
		$charactersPerUser = array();
		foreach (OAuth2Users::getAllCharacters() as $character) {
			$charactersPerUser[$character->id_forum_user][] = $character;
		}

		$updateStatus = array();
		foreach ($charactersPerUser as $userId => $characters) {
			$isDirector = false;
			$isInCorporation = false;

			foreach ($characters as $character) {
				$esi = EsiFactory::createEsi($character);
				try {
					$res = $esi->invoke(
						"get",
						"/characters/{character_id}/",
						array("character_id" => $character->id_entity)
					);

					// Retrieve the raw JSON of the current player
					$json = json_decode($res->raw, true);
					$eveCharacter = CharacterDetails::create(
						$character->id_entity,
						$json
					);

					// At least 1 character must be in the corporation
					$curIsInCorporation = $eveCharacter->getCorporationId() == CORPORATION_ID;
					$isInCorporation = $isInCorporation || $curIsInCorporation;
					// In right corp and not director (yet) ? Check if he's
					if (!$isDirector && $curIsInCorporation) {
						$res = $esi->invoke(
							"get",
							"/characters/{character_id}/roles/",
							array("character_id" => $character->id_entity)
						);
						// Retrieve the raw JSON
						$json = json_decode($res->raw, true);
						$roles = CharacterRoles::create($json);
						$isDirector = $isDirector || in_array("Director", $roles->getRoles());
					}
				} catch (RequestFailedException $ex) {
					;
				}
			}
			$updateStatus[$userId]['in_corp'] = $this->updateUserAndGroups(
				$userId,
				PHPBB_GROUP_VERIFIED_ID,
				$isInCorporation
			);
			// If the guy is in the corp, add him as a friend
			if ($isInCorporation) {
				$this->updateUserAndGroups(
					$userId,
					PHPBB_GROUP_FRIEND_ID,
					// Never remove the group: manual configuration
					true,
					false
				);
			}
			$updateStatus[$userId]['is_director'] = $this->updateUserAndGroups(
				$userId,
				PHPBB_GROUP_DIRECTOR_ID,
				$isInCorporation && $isDirector
			);
		}
		return new JsonView($updateStatus);
	}

	/**
	 * Updates the user PhpBB groups.
	 *
	 * @param int $userId the PhpBB user ID
	 * @param int $phpbbGroup the ID of the PhpBB group
	 * @param bool $match should he be in or out ?
	 * @param bool $defaultGroup should the group be the default one ? (true by default)
	 * @return string the result
	 */
	private function updateUserAndGroups(
		int $userId,
		int $phpbbGroup,
		bool $match,
		bool $defaultGroup = true
	) {
		// The user is in, add the phpBB group
		if ($match) {
			// If the user is already in the right PhpBB group
			if (PhpBB::isUserInGroup($userId, $phpbbGroup)) {
				return "already in";
			} else {
				$inCorp = PhpBB::addUserInGroup($userId, $phpbbGroup, $defaultGroup);
				return $inCorp === false ? "now in" : "FAIL: " . $inCorp;
			}
		}
		// The user is NOT in, remove his phpBB group
		else {
			if (PhpBB::isUserInGroup($userId, $phpbbGroup)) {
				$inCorp = PhpBB::removeUserFromGroup($userId, $phpbbGroup);
				return $inCorp === false ? "now out" : "FAIL: " . $inCorp;
			} else {
				return "already out";
			}
		}
	}

}
