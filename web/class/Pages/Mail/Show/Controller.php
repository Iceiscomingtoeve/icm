<?php

namespace Pages\Mail\Show;

use Controller\AController;
use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use Pages\Mail\Show\Views\Success;
use View\Errors\NoActiveCharacterError;
use View\Errors\NotConnectedForumError;
use View\View;

/**
 * Handles the show action in Mail page
 *
 * @package Pages\Mail\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		} else if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		return new Success(
			MailLabel::invoke($oauthUser),
			MailList::invoke($oauthUser),
			!empty($params) && intval($params[0]) > 0 ? intval($params[0]) : 1
		);
	}

}
