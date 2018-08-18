<?php

namespace Utils\Builder;

class EVEImage implements Builder {

	/**
	 * @param int $characterId the EVE character ID
	 * @param int $width the width of the portrait (16, 32, 64, 128)
	 * @return string the full URL path to the character portrait
	 */
	public static function getCharacterImage(
		int $characterId,
		int $width = 32
	) {
		return IMAGE_SERVER_URL . "/Character/" . $characterId . "_" . $width . ".jpg";
	}

	/**
	 * @param int $corporationId the EVE corporation ID
	 * @param int $width the width of the portrait (16, 32, 64, 128)
	 * @return string the full URL path to the corporation portrait
	 */
	public static function getCorporationImage(
		int $corporationId,
		int $width = 64
	) {
		return IMAGE_SERVER_URL . "/Corporation/" . $corporationId . "_" . $width . ".png";
	}

	/**
	 * @param int $itemId the EVE item/type ID
	 * @param int $width the width of the portrait (16, 32, 64, 128)
	 * @return string the full URL path to the item portrait
	 */
	public static function getItemImage(
		int $itemId,
		int $width = 32
	) {
		return IMAGE_SERVER_URL . "/Type/" . $itemId . "_" . $width . ".png";
	}

}
