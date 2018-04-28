<?php

namespace Controller;

use EVEOnline\OAuth\Login;
use Model\Bean\OAuth2Users;
use Model\Session\EVECharacter;
use Utils\Utils;
use View\View;

/**
 * Handles the Callback page
 */
final class Callback extends AController {

	public function show(array $params = array()) {
		Utils::redirect("/");
	}

	/**
	 * Called when the SSO login is done by a user.
	 *
	 * @param array $params
	 * @return View the view, will always be null
	 */
	public function login(array $params = array()) {
		// The code was not given, might be a manual call to this page.
		if (!array_key_exists("code", $params)) {
			Utils::redirect("/");
		}
		$code = $params['code'];
		// The code was empty, might be a manual call to this page.
		if (empty($code)) {
			Utils::redirect("/");
		}

		$tokenRetriever = new Login(
			array(
				"clientId" => ESI_CLIENT_ID,
				"clientSecret" => ESI_SECRET_KEY,
				"redirectUri" => urlencode(ESI_CALLBACK_URL)
			)
		);
		$token = $tokenRetriever->getAccessToken("authorization_code", array("code" => $code));

		// We got an access token, let's now get the user's details
		$user = $tokenRetriever->getResourceOwner($token);
		// Save in DataBase access + refresh tokens and character
		$oauthUser = new OAuth2Users();
		$oauthUser->access_token = $token->getToken();
		$oauthUser->refresh_token = $token->getRefreshToken();
		$oauthUser->expire_time = $token->getExpires();
		$oauthUser->id_character = $user->getId();
		$oauthUser->id_forum_user = parent::getPhpbbHandler()->getUser()->data['user_id'];
		$oauthUser->character_name = $user->getName();
		$oauthUser->is_main_character = empty($this->charactersOAuth) ? true : false;

		$oauthUser->insert();
		Utils::redirect("/");
		return NULL;
	}

	/**
	 * Called when the user wants to change his current character
	 *
	 * @param array $params
	 * @return View the view, will always be null
	 */
	public function change_character(array $params = array()) {
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
