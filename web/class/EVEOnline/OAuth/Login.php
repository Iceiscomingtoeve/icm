<?php

namespace EVEOnline\OAuth;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * Main class to retrieve the access token and refresh token from the SSO code
 *
 * @package EVEOnline\OAuth
 */
class Login extends AbstractProvider {

	use BearerAuthorizationTrait;

	/**
	 * Get authorization url to begin OAuth flow.
	 *
	 * @return string
	 */
	public function getBaseAuthorizationUrl() {
		return ESI_LOGIN_BASE_URL . "/oauth/authorize";
	}

	/**
	 * Get access token url to retrieve token.
	 *
	 * @param  array $params
	 * @return string
	 */
	public function getBaseAccessTokenUrl(array $params) {
		return ESI_LOGIN_BASE_URL . "/oauth/token";
	}

	/**
	 * Get provider url to fetch user details.
	 *
	 * @param AccessToken $token the token
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token) {
		return ESI_LOGIN_BASE_URL . "/oauth/verify";
	}

	/**
	 * Retrieves the resource owner.
	 *
	 * @param AccessToken $token the token
	 * @return LoginResourceOwner the resource owner
	 */
	public function getResourceOwner(AccessToken $token) {
		return $this->createResourceOwner(
			$this->fetchResourceOwnerDetails($token),
			$token
		);
	}

	/**
	 * Get the default scopes used by this provider.
	 *
	 * This should not be a complete list of all scopes, but the minimum
	 * required for the provider user interface!
	 *
	 * @return array
	 */
	protected function getDefaultScopes() {
		return array();
	}

	/**
	 * Check a provider response for errors.
	 *
	 * @param ResponseInterface $response
	 * @param array|string $data Parsed response data
	 * @throws IdentityProviderException
	 */
	protected function checkResponse(ResponseInterface $response, $data) {
		if (!empty($data['error'])) {
			throw new IdentityProviderException($data['error_description'], $response->getStatusCode(), $data);
		}
	}

	/**
	 * Generate a user object from a successful user details request.
	 *
	 * @param array $response
	 * @param AccessToken $token
	 * @return LoginResourceOwner
	 */
	protected function createResourceOwner(array $response, AccessToken $token) {
		return new LoginResourceOwner($response);
	}

}
