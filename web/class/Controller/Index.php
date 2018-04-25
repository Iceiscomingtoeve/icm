<?php

namespace Controller;

use EVEOnline\ESI\Character\Channel;
use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\EsiFactory;
use Model\Table\OAuth2Users;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show(array $params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new \View\Index\Show\Error();
		}
		// Retrieves characters from the player
		$characters = array();
		$charactersOAuth = OAuth2Users::getCharacterFromUserId($this->getPhpbbHandler()->getUser()->data['user_id']);
		foreach ($charactersOAuth as $character) {
			$esi = EsiFactory::createEsi($character);
			//TODO: Handles properly the API lost
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/",
				array("character_id" => $character->id_character)
			);

			// Retrieve the raw JSON
			$json = json_decode($res->raw, true);
			$characters[] = CharacterDetails::create(
				$character->id_character,
				$json
			);
		}
		return new \View\Index\Show\Success($characters);
	}

	public function channels(array $params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous() ||
			empty($params)
		) {
			return new \View\Index\Channels\Error("Vous devez être connecté sur le forum");
		}

		// Retrieves characters from the player
		$characterOAuth = OAuth2Users::getCharacterFromCharacterId($params[0]);
		if (is_null($characterOAuth)) {
			return new \View\Index\Channels\Error("Vous devez sélectionner un personnage");
		}

		$esi = EsiFactory::createEsi($characterOAuth);
		try {
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/chat_channels/",
				array("character_id" => $characterOAuth->id_character)
			);
		} catch (EsiScopeAccessDeniedException $ex) {
			return new \View\Index\Channels\Error("Vous n'avez pas autorisé la lecture des channels depuis l'ESI");
		}
		// Retrieve the raw JSON
		$channels = array();
		$rawChannels = json_decode($res->raw, true);
		foreach ($rawChannels as $rawChannel) {
			$channels[] = Channel::create($rawChannel);
		}
		return new \View\Index\Channels\Success($channels);
	}

}
