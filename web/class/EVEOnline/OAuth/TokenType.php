<?php

namespace EVEOnline\OAuth;

use Utils\Enum\BasicEnum;

/**
 * Token Type for any OAuth Token.
 *
 * @package EVEOnline\OAuth
 */
final class TokenType extends BasicEnum {

	const CHARACTER = "character";
	const CORPORATION = "corporation";
	const ALLIANCE = "alliance";

}
