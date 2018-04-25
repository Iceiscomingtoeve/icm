<?php

namespace Model;

use Utils\Handler\ErrorHandler;

/**
 * Handles every bean in the DataBase
 */
abstract class Model {

	/**
	 * Primary field name
	 * @var string
	 */
	protected $primaryField;

	/**
	 * Unique fields (columns names)
	 * @var array
	 */
	protected $uniqueFields;

	/**
	 * @var string $table the table name
	 */
	protected $table;

	/**
	 * Creates a common Model bean
	 *
	 * @param string $table the table name with the schema
	 * @param array $uniqueFields list of unique field (column names)
	 * @param string $primaryField the name of column of the primary key
	 */
	public function __construct(
		$table,
		$uniqueFields = array(),
		$primaryField = "id"
	) {
		$this->table = $table;
		$this->uniqueFields = $uniqueFields;
		$this->primaryField = $primaryField;
	}

	/**
	 * @return string the class name
	 */
	public final function __toString() {
		return get_class($this);
	}

	/**
	 * Inserts new line in DataBase.<br>
	 * In case of duplicate entry, it will update non unique fields.
	 *
	 * @param bool $ignore should it be an insert ignore ? (false by default)
	 * @return bool true if the insert is done, false otherwise
	 */
	public final function insert(
		$ignore = false
	) {
		$properties = self::getProperties($this);
		$columns = array_keys($properties);
		$values = array_values($properties);

		$columnOnUpdate = array();
		foreach ($columns as $column) {
			if (strcmp($column, $this->primaryField) == 0 ||
				in_array($column, $this->uniqueFields)
			) {
				continue;
			}
			$columnOnUpdate[] = $column . " = VALUES(" . $column . ")";
		}

		$sql = "
	INSERT " . ($ignore ? "IGNORE " : "") . "INTO
	" . $this->table . "
	(" . implode(", ", $columns) . ")
	VALUES
	(" . implode(", ", MySQL::createBindingArray($columns)) . ")
	ON DUPLICATE KEY UPDATE
	" . implode(", ", $columnOnUpdate) . ";";

		$db = new MySQL();
		$statement = $db->prepare($sql);
		$status = $statement->execute($values);
		$statement = NULL;

		// Sets back the ID
		if ($status) {
			$this->{$this->primaryField} = $db->lastInsertId();
		}
		return $status;
	}

	/**
	 * Updates the current bean from DataBase
	 *
	 * @return bool true if the insert is done, false otherwise
	 */
	public final function update() {
		$properties = self::getProperties($this);
		$columns = array_keys($properties);
		$values = array_values($properties);

		$setArray = array();
		for ($i = 0; $i < count($properties); $i++) {
			$column = $columns[$i];
			if (strcmp($column, $this->primaryField) == 0 ||
				in_array($column, $this->uniqueFields)
			) {
				// Remove the value for binding
				unset($values[$i]);
				continue;
			}
			$setArray[] = $column . " = ?";
		}

		$sql = "
	UPDATE
		" . $this->table . "
	SET
		" . implode(", ", $setArray) . "
	WHERE
		" . $this->primaryField . " = ?
	;";
		// Adds the primary key binding
		$values[] = $this->{$this->primaryField};

		$db = new MySQL();
		$statement = $db->prepare($sql);
		$status = $statement->execute($values);
		$statement = NULL;

		return $status;
	}

	/**
	 * Removes the current bean from Database
	 *
	 * @return integer the number of deleted bean, -1 in case of error
	 */
	public final function delete() {
		$sql = "
	DELETE FROM
		" . $this->table . "
	WHERE
		" . $this->primaryField . " = ?
	;";

		$db = new MySQL();
		$statement = $db->prepare($sql);
		$status = $statement->execute(array($this->{$this->primaryField}));
		$statement = NULL;

		return $status;
	}

	/**
	 * Retrieves properties of the given object.
	 *
	 * @param object $object any object
	 * @return array the properties on its column name and its value
	 */
	private static function getProperties($object) {
		$properties = array();
		try {
			$reflect = new \ReflectionClass(get_class($object));
			foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
				$properties[$property->getName()] = $property->getValue($object);
			}
		} catch (\ReflectionException $ex) {
			ErrorHandler::logException($ex);
		}
		return $properties;
	}

}
