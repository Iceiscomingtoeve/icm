<?php

namespace EVEOnline\ESI\Utils\Enums;

use Utils\Enum\BasicEnum;

/**
 * Entity Type.
 *
 * @package EVEOnline\ESI\Utils\Enums
 */
final class EntityType extends BasicEnum {

	const FACTION = "faction";
	const ALLIANCE = "alliance";
	const CORPORATION = "corporation";
	const CHARACTER = "character";
	const AGENT = "agent";

	const REGION = "region";
	const CONSTELLATION = "constellation";
	const SOLAR_SYSTEM = "solar_system";
	const STATION = "station";

	const INVENTORY_TYPE = "inventory_type";

}
