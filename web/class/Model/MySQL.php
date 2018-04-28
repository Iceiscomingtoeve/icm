<?php

namespace Model;

use Utils\Utils;

/**
 * Interface between MySQL and PDO.
 */
final class MySQL extends \PDO {

	/**
	 * BDD constructor.
	 */
	public function __construct() {
		try {
			parent::__construct(
				"mysql:dbname=" . str_replace("`", "", constant("DB_NAME")) . ";" .
				"host=" . constant("DB_URL") . ";" .
				"port=" . constant("DB_PORT") . ";" .
				"charset=utf8mb4;",
				constant("DB_LOGIN"),
				constant("DB_PASSWORD"),
				array(
					// Allows to return the real value of row updated instead of 0 if nothing changed
					\PDO::MYSQL_ATTR_FOUND_ROWS => true,
					// Better to rely on DataBase engine to prepare the query
					\PDO::ATTR_EMULATE_PREPARES => false,
					// Better throw exception than silent errors
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
					\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
				)
			);
		} catch (\PDOException $ex) {
			die("Impossible to connect to Database: " . $ex->getMessage() . " !");
		}
	}

	/**
	 * Execute the SQL query and returns a bean.
	 *
	 * @param string $sql sql query to send
	 * @param string $className the class name
	 * @param array $bindings the array for value bindings
	 * @return array array of bean that match the SQL query
	 */
	public final function objExec(
		string $sql,
		string $className,
		array $bindings = array()
	) {
		$statement = parent::prepare($sql);
		try {
			$statement->execute($bindings);
			return $statement->fetchAll(\PDO::FETCH_CLASS, $className);
		} catch (\PDOException $ex) {
			$this->logSqlError($statement);
		}
		return NULL;
	}

	/**
	 * Creates the array of "?" for SQL query.
	 *
	 * @param array $array the column/value array
	 * @return array the array of question marks
	 */
	public static function createBindingArray(array $array = array()) {
		if (!is_array($array) || is_null($array) || empty($array)) {
			return array();
		}
		$ret = array();
		for ($i = 0; $i < count($array); $i++) {
			$ret[] = "?";
		}
		return $ret;
	}

	/**
	 * Logs the SQL error into file + send mail
	 *
	 * @param \PDOStatement $statement PDOStatement or NULL if it was a raw query
	 */
	private function logSqlError(\PDOStatement $statement = NULL) {
		$sqlQuery = "UNKNOWN";
		if (!is_null($statement) &&
			$statement instanceof \PDOStatement
		) {
			$sqlQuery = $statement->queryString;
		}
		$errorLog = $statement->errorInfo();

		$prefix = "[" . Utils::dateJJ_MM_AAAA(true, time()) . "] ";
		$message = $prefix . "SQLSTATE: " . $errorLog[0] . "\n";
		$message .= $prefix . "Erreur numéro: " . $errorLog[1] . "\n";
		$message .= $prefix . "Message d'erreur: " . $errorLog[2] . "\n";
		$message .= $prefix . "Requête SQL: '" . $sqlQuery . "'\n";
		$message .= Utils::callStack(false);
		$message .= str_repeat("=", 60) . "\n";

		// Write into the log file
		$logSQL = fopen(PATH_LOG_SQL_ERROR, "a");
		fwrite($logSQL, $message, strlen($message));
		fclose($logSQL);

		// Sends an email
		$message = str_replace("\n", "<br>", $message);
		$message = "<html><body><h1>Une erreur SQL est survenue sur le site de EVEMyAdmin !</h1>" . $message;
		$message .= "<br><br>Cette erreur a aussi été loggé dans le fichier " . PATH_LOG_SQL_ERROR . ".</body></html>";
		Utils::sendMail(
			"EMA - Erreur SQL le " . Utils::dateJJ_MM_AAAA(true, time()),
			$message,
			MAIL_DEVELOPER
		);
	}

}
