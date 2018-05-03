<?php

namespace Utils;

/**
 * AutoLoader.<br>
 * Automatically includes class when being created.
 *
 * @see spl_autoload_register()
 */
final class AutoLoader {

	/**
	 * Register the custom spl loader.
	 */
	public static function register() {
		spl_autoload_register("Utils\\AutoLoader::loader");
	}

	/**
	 * Loads the class according to its full name
	 *
	 * @param string $className the full class name
	 * @return bool true if the class exists and is included, false otherwise
	 */
	public static function loader(string $className) {
		$classToPath = str_replace("\\", DIRECTORY_SEPARATOR, $className);

		// Self class
		$filePath = PATH_CLASS . "/" . $classToPath . ".php";
		if (file_exists($filePath)) {
			require_once $filePath;
			return class_exists($className);
		}
		return false;
	}

}
