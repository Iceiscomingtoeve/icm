<?php

namespace Utils\Builder;

use Controller\AController;
use Dispatcher\ADispatcher;
use phpbb\request\request_interface;
use Utils\Handler\PhpBB;

/**
 * Class BreadcrumbBuilder.
 *
 * @package Utils\Builder
 */
class BreadcrumbBuilder implements Builder {

	public static function createBreadcrumb() {
		$breadcrumb = "<ol class=\"breadcrumb float-right\">";

		$page = self::getVariableFromGet("page");
		if (!empty($page)) {
			$breadcrumb .= "<li class=\"breadcrumb-item\"><a href=\"/\">Home</a></li>";
			$action = self::getVariableFromGet("action");
			if (!empty($action) && strcasecmp($action, AController::DEFAULT_ACTION) != 0) {
				$breadcrumb .= "<li class=\"breadcrumb-item\"><a href=\"/" . $page . "\">" . self::showInfoInBreadcrumb($page) . "</a></li>";
				$breadcrumb .= "<li class=\"breadcrumb-item active\">" . self::showInfoInBreadcrumb($action) . "</li>";
			} else if (strcasecmp($page, ADispatcher::DEFAULT_CONTROLLER) != 0) {
				$breadcrumb .= "<li class=\"breadcrumb-item active\">" . self::showInfoInBreadcrumb($page) . "</li>";
			}
		} else {
			$breadcrumb .= "<li class=\"breadcrumb-item active\">Home</li>";
		}

		return $breadcrumb . "</ol>";
	}

	private static function getVariableFromGet(
		string $name
	) {
		return str_replace(
			"-",
			"_",
			PhpBB::getInstance()->getRequest()->variable(
				$name,
				"",
				true,
				request_interface::GET
			)
		);
	}

	private static function showInfoInBreadcrumb($value) {
		return ucfirst(ucwords(str_replace('_', ' ', $value)));
	}

}
