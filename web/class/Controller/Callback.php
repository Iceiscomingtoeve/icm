<?php

namespace Controller;

use EVEOnline\OAuth\Login;
use Model\Table\OAuth2Users;
use Utils\Utils;

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
	 * @return string state of the controller
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
		$oauthUser->insert();
		Utils::redirect("/");
		return NULL;
	}

}
