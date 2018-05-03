<?php

namespace Model\Session;
use Model\Bean\OAuth2Users;

/**
 * The EVE Character for the UserSession.
 *
 * @package Model\Session
 */
class EVECharacter {

	/** @var OAuth2Users $oauthUser the OAuth2Users */
	private $oauthUser;

	/** @var bool $isMain is he the main character of the user ? */
	private $isMain;

	/** @var string $name the name of the character */
	private $name;

	/** @var int the character ID from EVE Online */
	private $characterId;

	/**
	 * EVECharacter constructor.
	 *
	 * @param OAuth2Users $oauthUser the authenticated user
	 */
	public function __construct(
		OAuth2Users $oauthUser
	) {
		$this->oauthUser = $oauthUser;

		$this->isMain = $oauthUser->is_main_character;
		$this->name = $oauthUser->entity_name;
		$this->characterId = $oauthUser->id_entity;
	}

	/**
	 * @return bool true if he/she is the main character, false otherwise
	 */
	public function isMain(): bool {
		return $this->isMain;
	}

	/**
	 * @return string the character name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return int the character ID
	 */
	public function getCharacterId(): int {
		return $this->characterId;
	}

	/**
	 * @return OAuth2Users
	 */
	public function getOauthUser(): OAuth2Users {
		return $this->oauthUser;
	}

}
