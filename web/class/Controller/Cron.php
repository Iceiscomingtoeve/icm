<?php

namespace Controller;

use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\Character\CharacterRoles;
use EVEOnline\ESI\EsiFactory;
use Model\Table\OAuth2Users;
use Seat\Eseye\Exceptions\RequestFailedException;
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
		// Require for checking group from the user
		require_once PATH_PHPBB . "/includes/functions_user.php";

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
					$inCorp = self::addUserInGroup($characterOAuth->id_forum_user, PHPBB_GROUP_VERIFIED_ID);
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
					$isDirector = self::addUserInGroup($characterOAuth->id_forum_user, PHPBB_GROUP_DIRECTOR_ID);
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

	/**
	 * Adds the given user in the given phpbb group.
	 *
	 * @param int $userId the user ID
	 * @param int $groupId the group ID
	 * @param bool $defaultGroup should it be his default group (true by default)
	 * @return string|bool false if no error occurred, string of I18n from PhpBB in case of error
	 */
	private static function addUserInGroup(
		int $userId,
		int $groupId,
		bool $defaultGroup = true
	) {
		// If the guy is not in the group yet, add him
		return !group_memberships($groupId, $userId, true) ?
			// see https://wiki.phpbb.com/Function.group_user_add
			group_user_add($groupId, $userId, false, false, $defaultGroup) :
			false;
	}

}
