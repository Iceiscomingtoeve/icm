<?php

namespace EVEOnline\OAuth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Main class to retrieve the access token and refresh token from the SSO code
 *
 * @package EVEOnline\OAuth
 */
class LoginResourceOwner implements ResourceOwnerInterface{

	/**
	 * Raw response
	 *
	 * @var array
	 */
	protected $response;

	/**
	 * Creates new resource owner.
	 *
	 * @param array $response
	 */
	public function __construct(array $response) {
		$this->response = $response;
	}

	/**
	 * Get resource owner id (character id).
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->response['CharacterID'] ?: NULL;
	}

	/**
	 * Get resource owner name (character name).
	 *
	 * @return string|null
	 */
	public function getName() {
		return $this->response['CharacterName'] ?: NULL;
	}

	/**
	 * Get resource type (character/corporation/alliance).
	 *
	 * @return TokenType|null
	 */
	public function getTokenType() {
		return new TokenType($this->response['TokenType']) ?: new TokenType(TokenType::CHARACTER);
	}

	/**
	 * Get character owner hash.
	 *
	 * @return string|null
	 */
	public function getOwnerHash() {
		return $this->response['CharacterOwnerHash'];
	}

	/**
	 * Return all of the owner details available as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->response;
	}

}
