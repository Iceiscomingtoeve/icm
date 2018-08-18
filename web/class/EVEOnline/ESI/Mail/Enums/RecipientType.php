<?php

namespace EVEOnline\ESI\Mail\Enums;

use Utils\Enum\BasicEnum;

/**
 * Recipient Type for any mail.
 *
 * @package EVEOnline\ESI\Mail\Enums
 */
final class RecipientType extends BasicEnum {

	const CHARACTER = "character";
	const CORPORATION = "corporation";
	const ALLIANCE = "alliance";

}
