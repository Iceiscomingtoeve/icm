<?php

require_once "fonctions.inc.php";

/**
 * Creates the file if missing.<br>
 * Will die if can't create it.
 *
 * @param string $filePath the path of the file to create if messing
 */
function createMissingFile($filePath = "") {
	if (is_null($filePath) || empty($filePath)) {
		return;
	}
	$openedFile = fopen($filePath, "a");
	if (!is_file($filePath)) {
		die("Impossible to create file: '" . $filePath . "' !");
	} else {
		fclose($openedFile);
	}
}

// Sets language, charset, TimeZone and other funny stuff
header("charset=UTF-8");
mb_internal_encoding("UTF-8");
date_default_timezone_set("Europe/Paris");
setlocale(LC_ALL, "fr_FR.UTF-8", "fr_FR.UTF8");

// Retrieve full path
$projectPath = realpath(__DIR__ . "/../");
$rootPath = realpath($projectPath . "/../");

// If the user agent gives nothing, it might be a bot/spy/other. Logs that in case
if (empty($_SERVER['HTTP_HOST'])) {
	Utils\Utils::callStack();
	Utils\Utils::log(print_r($_SERVER, true), time());
	die;
}

// Sets value into CONSTANT
define("PATH_PROJECT", $projectPath, false);
define("PATH_ROOT", $rootPath, false);
unset($projectPath);
unset($rootPath);

define("PATH_LOG_PHP_ERROR", PATH_ROOT . "/log/php_errors.log", false);
define("PATH_LOG_SQL_ERROR", PATH_ROOT . "/log/sql_errors.log", false);

define("PATH_INCLUDE", PATH_PROJECT . "/inc", false);
define("PATH_COMPOSER", PATH_PROJECT . "/vendor", false);
define("PATH_CONFIG", PATH_PROJECT . "/conf", false);
define("PATH_CLASS", PATH_PROJECT . "/class", false);
define("PATH_UTILITY", PATH_CLASS . "/Utils", false);
define("PATH_CONTROLLER", PATH_CLASS . "/Controller", false);

// Creates PHP log file
createMissingFile(PATH_LOG_PHP_ERROR);
// Creates SQL log file
createMissingFile(PATH_LOG_SQL_ERROR);

// Reading main config file
$mainConfigFile = PATH_CONFIG . "/config.ini";
$iniConfig = parse_ini_file($mainConfigFile, false, INI_SCANNER_TYPED);
if (!$iniConfig) {
	die("Impossible to read config file: '" . $mainConfigFile . "' !");
}
unset($mainConfigFile);

// Saves every error (even NOTICE and WARNING)
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("log_errors_max_len", 1024);
// Sets the file path for PHP NOTICE, WARNING, FATAL, etc.
ini_set("error_log", PATH_LOG_PHP_ERROR);
// Print error if asked for
ini_set("display_errors", $iniConfig['show_errors'] ? "1" : "0");

// Sets the temporary file (for upload) in the right folder
ini_set("upload_tmp_dir", PATH_ROOT . "/tmp");

// Raise PHP memory limit to 64MB
ini_set("memory_limit", "64M");
// Sets the active domain to the current domain name
ini_set("session.cookie_domain", "." . $iniConfig['domain']);
session_set_cookie_params(0, "/", "." . $iniConfig['domain'], true, true);

// Domain and application config
define("DOMAIN", $iniConfig['domain'], false);
define("FULL_DOMAIN", "http://" . DOMAIN, false);

// Cookie configuration
define("DISCLAIMER_NAME", $iniConfig['disclaimer_name'], false);
define("COOKIE_DEFAULT_EXPIRATION", $iniConfig['default_expiration'], false);
// Session architecture
define("SESSION_CURRENT_URI", "current_uri", false);
define("SESSION_EVE_CHARACTERS", "characters", false);
define("SESSION_MAIN_CHARACTER", "main_character", false);

// Mail addresses in case of error
define("MAIL_DEVELOPER", $iniConfig['developer'], false);
define("MAIL_ADMINISTRATOR", $iniConfig['admin'], false);

// Database login/password
define("DB_LOGIN", $iniConfig['login'], false);
define("DB_PASSWORD", $iniConfig['password'], false);
define("DB_URL", $iniConfig['url'], false);
define("DB_PORT", $iniConfig['port'], false);
define("DB_NAME", "`" . $iniConfig['schema_name'] . "`", false);

// Define of RE-CAPTCHA v2 from Google
define("RE-CAPTCHA_PUBLIC", $iniConfig['recaptcha_public'], false);
define("RE-CAPTCHA_PRIVATE", $iniConfig['recaptcha_private'], false);

// Define of EVE Online EsiFactory system
define("ESI_BASE_URL", $iniConfig['esi_base_url'], false);
define("ESI_LOGIN_BASE_URL", "https://login.eveonline.com", false);
define("ESI_CLIENT_ID", $iniConfig['client_id'], false);
define("ESI_SECRET_KEY", $iniConfig['secret_key'], false);
define("ESI_CALLBACK_URL", $iniConfig['callback_url'], false);

define("PATH_ESI_CACHE", PATH_PROJECT . "/esi-cache/", false);
define("PATH_ESI_LOG", PATH_ROOT . "/log/esi.log", false);

// Define of EVE Online utility stuff
define("IMAGE_SERVER_URL", $iniConfig['image_server_url'], false);

// PhpBB config part
define("PHPBB_URL", $iniConfig['phpbb_url'], false);
define("PATH_PHPBB", $iniConfig['phpbb_path'], false);

// Some define for link between ESI and phpBB
define("CORPORATION_ID", $iniConfig['corporation_id'], false);
define("PHPBB_GROUP_VERIFIED_ID", $iniConfig['phpbb_group_verified_id'], false);
define("PHPBB_GROUP_DIRECTOR_ID", $iniConfig['phpbb_group_director_id'], false);
define("PHPBB_GROUP_FRIEND_ID", $iniConfig['phpbb_group_friend_id'], false);

// Security measure
unset($iniConfig);

// Includes the class AutoLoader
require_once PATH_UTILITY . "/AutoLoader.php";
Utils\AutoLoader::register();

// PhpBB links
define("IN_PHPBB", true);
$phpEx = "php";
$phpbb_root_path = PATH_PHPBB . "/";
require_once PATH_PHPBB . "/common." . $phpEx;

require_once PATH_COMPOSER . "/autoload.php";

// Includes the ErrorHandler
Utils\Handler\ErrorHandler::register();
